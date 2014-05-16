<?php

namespace SimpleIT\ClaireExerciseBundle\Repository\Exercise\DomainKnowledge;

use Doctrine\ORM\QueryBuilder;
use SimpleIT\ClaireExerciseBundle\Entity\User\User;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\CoreBundle\Model\Paginator;
use SimpleIT\CoreBundle\Repository\BaseRepository;
use SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge\Knowledge;
use SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge\OwnerKnowledge;
use SimpleIT\ClaireExerciseBundle\Exception\FilterException;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\Sort;

/**
 * OwnerKnowledge Repository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class OwnerKnowledgeRepository extends BaseRepository
{
    /**
     * Find an owner knowledge by its id
     *
     * @param mixed $ownerKnowledgeId
     *
     * @return OwnerKnowledge
     * @throws NonExistingObjectException
     */
    public function find($ownerKnowledgeId)
    {
        $ownerKnowledge = parent::find($ownerKnowledgeId);
        if ($ownerKnowledge === null) {
            throw new NonExistingObjectException();
        }

        return $ownerKnowledge;
    }

    /**
     * Find a list of knowledges according to a type an to metadata contained in a
     * collection information
     *
     * @param CollectionInformation $collectionInformation
     * @param User                  $owner
     * @param Knowledge             $knowledge
     *
     * @throws FilterException
     * @return array
     */
    public function findAll(
        $collectionInformation = null,
        $owner = null,
        $knowledge = null
    )
    {
        $metadata = array();
        $keywords = array();

        $qb = $this->createQueryBuilder('owr');
        $qb->leftJoin('owr.knowledge', 'r');

        if (!is_null($owner)) {
            $qb->where(
                $qb->expr()->eq(
                    'owr.owner',
                    $owner->getId()
                )
            );
        }

        if (!is_null($knowledge)) {
            $qb->andWhere(
                $qb->expr()->eq(
                    'owr.knowledge',
                    $knowledge->getId()
                )
            );
        }

        // Collection information
        if ($collectionInformation !== null) {
            $filters = $collectionInformation->getFilters();
            foreach ($filters as $filter => $value) {
                switch ($filter) {
                    case ('type'):
                        if (is_array($value)) {
                            $qpType = '';
                            foreach ($value as $val) {
                                if ($qpType !== '') {
                                    $qpType = $qb->expr()->orX(
                                        $qpType,
                                        $qb->expr()->eq('r.type', "'" . $val . "'")
                                    );
                                } else {
                                    $qpType = $qb->expr()->eq('r.type', "'" . $val . "'");
                                }
                            }
                            $qb->andWhere($qpType);
                        } else {
                            $qb->andWhere($qb->expr()->eq('r.type', "'" . $value . "'"));
                        }
                        break;
                    case ('metadata'):
                        $metadata = $this->metadataToArray($value);
                        break;
                    case ('keywords'):
                        $keywords = $this->keywordsToArray($value);
                        break;
                    case ('public-except-user'):
                        // knowledges not covered by ownerKnowledges
                        if (!is_numeric($value)) {
                            throw new FilterException('public-except-user filter must be numeric');
                        }
                        $qb = $this->addPublicExceptUser($qb, $value);
                        break;
                }
            }
            $sorts = $collectionInformation->getSorts();
        } else {
            $sorts = null;
        }

        // Metadata
        $i = 0;
        foreach ($metadata as $metaKey => $value) {
            $alias = 'm' . $i;
            $qb->leftJoin('owr.metadata', $alias);

            $qb->andWhere(
                $qb->expr()->andX(
                    $qb->expr()->eq($alias . '.key', "'" . $metaKey . "'"),
                    $qb->expr()->eq($alias . '.value', "'" . $value . "'")
                )
            );

            $i++;
        }

        // Misc keywords
        foreach ($keywords as $keyword) {
            $alias = 'm' . $i;
            $qb->leftJoin('owr.metadata', $alias);

            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->eq($alias . '.key', "'" . $keyword . "'"),
                    $qb->expr()->like($alias . '.value', "'%" . $keyword . "%'")
                )
            );

            $i++;
        }

        if (empty($sorts)) {
            $qb->addOrderBy('owr.id', 'ASC');
        } else {
            foreach ($sorts as $sort) {
                /** @var Sort $sort */
                switch ($sort->getProperty()) {
                    case 'type':
                        $qb->addOrderBy('r.type', $sort->getOrder());
                        break;
                    case 'author':
                        $qb->addOrderBy('r.author', $sort->getOrder());
                        break;
                    case 'id':
                        $qb->addOrderBy('r.id', $sort->getOrder());
                        break;
                }
            }
        }
        $qb = $this->setRange($qb, $collectionInformation);

        $qb->addGroupBy('owr.id, r.id');

        return new Paginator($qb);
    }

    /**
     * Find a knowledge if it is owned by the user
     *
     * @param      $knowledgeId
     * @param User $owner
     *
     * @return mixed
     * @throws NonExistingObjectException
     */
    public function findByIdAndOwner($knowledgeId, User $owner)
    {
        $queryBuilder = $this->createQueryBuilder('owr');
        $queryBuilder->leftJoin(
            'owr.knowledge',
            'k'

        );

        $queryBuilder->where($queryBuilder->expr()->eq('owr.owner', $owner->getId()));
        $queryBuilder->andWhere($queryBuilder->expr()->eq('k.id', $knowledgeId));

        $result = $queryBuilder->getQuery()->getResult();

        if (empty($result)) {
            throw new NonExistingObjectException('Unable to find knowledge ' . $knowledgeId .
            ' for owner ' . $owner->getId());
        } else {
            return $result[0];
        }
    }

    /**
     * Find an owner knowledge by knowledge
     *
     * @param int       $ownerKnowledgeId
     * @param Knowledge $knowledge
     *
     * @return mixed
     * @throws NonExistingObjectException
     */
    public function findByIdAndKnowledge($ownerKnowledgeId, Knowledge $knowledge)
    {
        $queryBuilder = $this->createQueryBuilder('owr');

        $queryBuilder->where($queryBuilder->expr()->eq('owr.knowledge', $knowledge->getId()));
        $queryBuilder->andWhere($queryBuilder->expr()->eq('owr.id', $ownerKnowledgeId));

        $result = $queryBuilder->getQuery()->getResult();

        if (empty($result)) {
            throw new NonExistingObjectException('Unable to find owner knowledge ' .
            $ownerKnowledgeId . ' for knowledge ' . $knowledge->getId());
        } else {
            return $result[0];
        }
    }

    /**
     * Converts the content of the metadata filter into a key => value array
     *
     * @param string|array $metadata
     *
     * @return array
     */
    private function metadataToArray($metadata)
    {
        $metadataArray = array();
        if (is_array($metadata)) {
            foreach ($metadata as $md) {
                $explode = explode(':', $md);
                $metadataArray[$explode[0]] = $explode[1];
            }
        } else {
            $explode = explode(':', $metadata);
            $metadataArray[$explode[0]] = $explode[1];
        }

        return $metadataArray;
    }

    /**
     * Convert the content of keywords filter into an array
     *
     * @param string|array $keywords
     *
     * @return array
     */
    private function keywordsToArray($keywords)
    {
        if (is_array($keywords)) {
            return $keywords;
        } else {
            return array($keywords);
        }
    }

    /**
     * Add the join and the constraints to the query builder to exclude knowledges already covered
     * by owner knowledges
     *
     * @param QueryBuilder $qb
     * @param              $userId
     *
     * @return QueryBuilder
     */
    private function addPublicExceptUser(QueryBuilder $qb, $userId)
    {
        $qb->andWhere(
            $qb->expr()->eq(
                'owr.public',
                'true'
            )
        );

        $notIn = $this->getEntityManager()->createQueryBuilder()
            ->select('re.id')
            ->from(
                'SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge\OwnerKnowledge',
                'owRes'
            )
            ->join('owRes.knowledge', 're')
            ->andWhere(
                $qb->expr()->eq(
                    'owRes.owner',
                    $userId
                )
            )
            ->getQuery()
            ->getDQL();

        $qb->andWhere($qb->expr()->notIn('owr.knowledge', $notIn));

        return $qb;
    }
}
