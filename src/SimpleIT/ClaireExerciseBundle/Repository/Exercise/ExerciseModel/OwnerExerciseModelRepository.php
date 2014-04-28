<?php

namespace SimpleIT\ClaireExerciseBundle\Repository\Exercise\ExerciseModel;

use Doctrine\ORM\QueryBuilder;
use SimpleIT\ClaireExerciseBundle\Entity\User\User;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\CoreBundle\Model\Paginator;
use SimpleIT\CoreBundle\Repository\BaseRepository;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\ExerciseModel;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\OwnerExerciseModel;
use SimpleIT\ClaireExerciseBundle\Exception\FilterException;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\Sort;

/**
 * OwnerExerciseModel repository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class OwnerExerciseModelRepository extends BaseRepository
{
    /**
     * Find an owner model by id
     *
     * @param int $ownerExerciseModelId
     *
     * @return OwnerExerciseModel
     * @throws NonExistingObjectException
     */
    public function find($ownerExerciseModelId)
    {
        $ownerExerciseModel = parent::find($ownerExerciseModelId);
        if ($ownerExerciseModel === null) {
            throw new NonExistingObjectException();
        }

        return $ownerExerciseModel;
    }

    /**
     * Find a list of resources according to a type an to metadata contained in a
     * collection information
     *
     * @param CollectionInformation $collectionInformation
     * @param User                  $owner
     * @param ExerciseModel         $exerciseModel
     *
     * @throws FilterException
     * @return array
     */
    public function findAll(
        CollectionInformation $collectionInformation = null,
        $owner = null,
        $exerciseModel = null
    )
    {
        $metadata = array();
        $keywords = array();

        $qb = $this->createQueryBuilder('owem');
        $qb->leftJoin('owem.exerciseModel', 'em');

        if (!is_null($owner)) {
            $qb->where(
                $qb->expr()->eq(
                    'owem.owner',
                    $owner->getId()
                )
            );
        }

        if (!is_null($exerciseModel)) {
            $qb->andWhere(
                $qb->expr()->eq(
                    'owem.exerciseModel',
                    $exerciseModel->getId()
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
                    case ('authorId'):
                        $qb->andWhere($qb->expr()->eq('em.author', "'" . $value . "'"));
                        break;
                    case ('owner'):
                        $qb->andWhere($qb->expr()->eq('owem.owner', $value));
                        break;
                    case ('draft'):
                        if ($value !== "true" && $value !== "false") {
                            throw new FilterException('draft filter must be true or false');
                        }
                        $qb->where($qb->expr()->eq('em.draft', "'" . $value . "'"));
                        break;
                    case ('complete'):
                        if ($value !== "true" && $value !== "false") {
                            throw new FilterException('complete filter must be true or false');
                        }
                        $qb->where($qb->expr()->eq('em.complete', "'" . $value . "'"));
                        break;
                    case ('public-except-user'):
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
            $qb->leftJoin('owem.metadata', $alias);

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
            $qb->leftJoin('owem.metadata', $alias);

            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->eq($alias . '.key', "'" . $keyword . "'"),
                    $qb->expr()->like($alias . '.value', "'%" . $keyword . "%'")
                )
            );

            $i++;
        }

        if (empty($sorts)) {
            $qb->addOrderBy('owem.id', 'ASC');
        } else {
            foreach ($sorts as $sort) {
                /** @var Sort $sort */
                switch ($sort->getProperty()) {
                    case 'type':
                        $qb->addOrderBy('em.type', $sort->getOrder());
                        break;
                    case 'author':
                        $qb->addOrderBy('em.author', $sort->getOrder());
                        break;
                    case 'title':
                        $qb->addOrderBy('em.title', $sort->getOrder());
                        break;
                    case 'id':
                        $qb->addOrderBy('em.id', $sort->getOrder());
                        break;
                }
            }
        }
        $qb = $this->setRange($qb, $collectionInformation);

        $qb->addGroupBy('owem.id, em.id');

        return new Paginator($qb);
    }

    /**
     * Find an owner exercise model by exercise model
     *
     * @param int           $ownerExerciseModelId
     * @param ExerciseModel $exerciseModel
     *
     * @return OwnerExerciseModel
     * @throws NonExistingObjectException
     */
    public function findByIdAndModel($ownerExerciseModelId, ExerciseModel $exerciseModel)
    {
        $queryBuilder = $this->createQueryBuilder('owem');

        $queryBuilder->where(
            $queryBuilder->expr()->eq(
                'owem.exerciseModel',
                $exerciseModel->getId()
            )
        );
        $queryBuilder->andWhere($queryBuilder->expr()->eq('owem.id', $ownerExerciseModelId));

        $result = $queryBuilder->getQuery()->getResult();

        if (empty($result)) {
            throw new NonExistingObjectException('Unable to find owner exercise model ' .
            $ownerExerciseModelId . ' for model ' . $exerciseModel->getId());
        } else {
            return $result[0];
        }
    }

    /**
     * Find an owner exercise model if it is owned by the user
     *
     * @param int  $ownerExerciseModelId
     * @param User $owner
     *
     * @return mixed
     * @throws NonExistingObjectException
     */
    public function findByIdAndOwner($ownerExerciseModelId, User $owner)
    {
        $queryBuilder = $this->createQueryBuilder('owem');
        $queryBuilder->leftJoin(
            'owem.exerciseModel',
            'em'

        );

        $queryBuilder->where($queryBuilder->expr()->eq('owem.owner', $owner->getId()));
        $queryBuilder->andWhere($queryBuilder->expr()->eq('em.id', $ownerExerciseModelId));

        $result = $queryBuilder->getQuery()->getResult();

        if (empty($result)) {
            throw new NonExistingObjectException('Unable to find exercise model ' .
            $ownerExerciseModelId . ' for owner ' . $owner->getId());
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
     * Add the join and the constraints to the query builder to exclude resources already covered
     * by owner resources
     *
     * @param QueryBuilder $qb
     * @param string       $userId
     *
     * @return QueryBuilder
     */
    private function addPublicExceptUser(QueryBuilder $qb, $userId)
    {
        $qb->andWhere(
            $qb->expr()->eq(
                'owem.public',
                'true'
            )
        );

        $notIn = $this->getEntityManager()->createQueryBuilder()
            ->select('exMod.id')
            ->from(
                'SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\OwnerExerciseModel',
                'owExMod'
            )
            ->join('owExMod.exerciseModel', 'exMod')
            ->andWhere(
                $qb->expr()->eq(
                    'owExMod.owner',
                    $userId
                )
            )
            ->getQuery()
            ->getDQL();

        $qb->andWhere($qb->expr()->notIn('owem.exerciseModel', $notIn));

        return $qb;
    }
}
