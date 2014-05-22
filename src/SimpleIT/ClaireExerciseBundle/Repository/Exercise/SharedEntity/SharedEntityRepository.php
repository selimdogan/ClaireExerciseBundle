<?php

namespace SimpleIT\ClaireExerciseBundle\Repository\Exercise\SharedEntity;

use Doctrine\DBAL\DBALException;
use Doctrine\ORM\QueryBuilder;
use SimpleIT\ClaireExerciseBundle\Entity\SharedEntity\SharedEntity;
use SimpleIT\ClaireExerciseBundle\Entity\User\User;
use SimpleIT\ClaireExerciseBundle\Exception\EntityAlreadyExistsException;
use SimpleIT\ClaireExerciseBundle\Exception\EntityDeletionException;
use SimpleIT\ClaireExerciseBundle\Exception\FilterException;
use SimpleIT\CoreBundle\Model\Paginator;
use SimpleIT\CoreBundle\Repository\BaseRepository;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\Sort;

/**
 * Abstract SharedEntityRepository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class SharedEntityRepository extends BaseRepository
{
    /**
     * Find a list of entities according to a type an to metadata contained in a
     * collection information
     *
     * @param CollectionInformation $collectionInformation
     * @param User                  $owner
     * @param User                  $author
     * @param SharedEntity          $parent
     * @param SharedEntity          $forkFrom
     * @param boolean               $isRoot
     * @param boolean               $isPointer
     *
     * @throws \SimpleIT\ClaireExerciseBundle\Exception\FilterException
     * @return array
     */
    public function findAll(
        $collectionInformation = null,
        $owner = null,
        $author = null,
        $parent = null,
        $forkFrom = null,
        $isRoot = null,
        $isPointer = null
    )
    {
        $metadata = array();
        $keywords = array();

        $qb = $this->createQueryBuilder('entity')
            ->select();

        if (!is_null($owner)) {
            $qb->where(
                $qb->expr()->eq(
                    'entity.owner',
                    $owner->getId()
                )
            );
        }

        if (!is_null($author)) {
            $qb->where(
                $qb->expr()->eq(
                    'entity.author',
                    $author->getId()
                )
            );
        }

        if (!is_null($parent)) {
            $qb->where(
                $qb->expr()->eq(
                    'entity.parent',
                    $parent->getId()
                )
            );
        }

        if (!is_null($forkFrom)) {
            $qb->where(
                $qb->expr()->eq(
                    'entity.forkFrom',
                    $forkFrom->getId()
                )
            );
        }

        if ($isPointer === true) {
            $qb->where($qb->expr()->isNotNull('entity.parent'));
        } else {
            $qb->where($qb->expr()->isNotNull('entity.content'));
        }

        if ($isRoot === true) {
            $qb->where($qb->expr()->isNull('entity.forkFrom'));
        }

        if ($collectionInformation !== null) {

            $filters = $collectionInformation->getFilters();
            foreach ($filters as $filter => $value) {
                switch ($filter) {
                    case ('id'):
                        $qb->andWhere($qb->expr()->eq('entity.id', $value));
                        break;
                    case ('author'):
                        $qb->andWhere($qb->expr()->eq('entity.author', "'" . $value . "'"));
                        break;
                    case ('owner'):
                        $qb->andWhere($qb->expr()->eq('entity.owner', $value));
                        break;
                    case ('type'):
                        if (is_array($value)) {
                            $qpType = '';
                            foreach ($value as $val) {
                                if ($qpType !== '') {
                                    $qpType = $qb->expr()->orX(
                                        $qpType,
                                        $qb->expr()->eq('entity.type', "'" . $val . "'")
                                    );
                                } else {
                                    $qpType = $qb->expr()->eq('entity.type', "'" . $val . "'");
                                }
                            }
                            $qb->andWhere($qpType);
                        } else {
                            $qb->andWhere($qb->expr()->eq('entity.type', "'" . $value . "'"));
                        }
                        break;
                    case ('metadata'):
                        $metadata = $this->metadataToArray($value);
                        break;
                    case ('keywords'):
                        $keywords = $this->keywordsToArray($value);
                        break;
                    case ('draft'):
                        if ($value !== "true" && $value !== "false") {
                            throw new FilterException('draft filter must be true or false');
                        }
                        $qb->where($qb->expr()->eq('entity.draft', "'" . $value . "'"));
                        break;
                    case ('complete'):
                        if ($value !== "true" && $value !== "false") {
                            throw new FilterException('complete filter must be true or false');
                        }
                        $qb->where($qb->expr()->eq('entity.complete', "'" . $value . "'"));
                        break;
                    case ('public-except-user'):
                        if (!is_numeric($value)) {
                            throw new FilterException('public-except-user filter must be numeric');
                        }
                        $qb = $this->addPublicExceptUser(
                            $qb,
                            $value,
                            $this->getClassMetadata()->getName()
                        );
                        break;
                }
            }

            // Metadata
            $i = 0;
            foreach ($metadata as $metaKey => $value) {
                $alias = 'm' . $i;
                $qb->leftJoin('entity.metadata', $alias);

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
                $qb->leftJoin('entity.metadata', $alias);

                $qb->andWhere(
                    $qb->expr()->orX(
                        $qb->expr()->eq($alias . '.key', "'" . $keyword . "'"),
                        $qb->expr()->like($alias . '.value', "'%" . $keyword . "%'")
                    )
                );

                $i++;
            }

            $sorts = $collectionInformation->getSorts();

            if (!empty($sorts)) {
                foreach ($sorts as $sort) {
                    /** @var Sort $sort */
                    switch ($sort->getProperty()) {
                        case 'title':
                            $qb->addOrderBy('entity.title', $sort->getOrder());
                            break;
                        case 'id':
                            $qb->addOrderBy('entity.id', $sort->getOrder());
                            break;
                        case 'type':
                            $qb->addOrderBy('entity.type', $sort->getOrder());
                            break;
                        case 'author':
                            $qb->addOrderBy('entity.author', $sort->getOrder());
                            break;
                    }
                }
                $qb = $this->setRange($qb, $collectionInformation);
            } else {
                $qb->addOrderBy('entity.id');
            }
        }

        return new Paginator($qb);
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
     * Add the join and the constraints to the query builder to exclude resources already covered
     * by entities
     *
     * @param QueryBuilder $qb
     * @param string       $userId
     * @param string       $entityClass
     *
     * @return QueryBuilder
     */
    private function addPublicExceptUser(QueryBuilder $qb, $userId, $entityClass)
    {
        $qb->andWhere(
            $qb->expr()->eq(
                'entity.public',
                'true'
            )
        );

        $notIn = $this->getEntityManager()->createQueryBuilder()
            ->select('entity2.id')
            ->from(
                $entityClass,
                'entity2'
            )
            ->andWhere(
                $qb->expr()->eq(
                    'entity2.owner',
                    $userId
                )
            )
            ->getQuery()
            ->getDQL();

        $qb->andWhere($qb->expr()->notIn('entity.id', $notIn));

        return $qb;
    }

    /**
     * Add a required resource to an entity
     *
     * @param int          $entityId
     * @param SharedEntity $required
     * @param string       $table
     * @param string       $reqEntityName
     *
     * @throws \SimpleIT\ClaireExerciseBundle\Exception\EntityAlreadyExistsException
     */
    protected function addRequired($entityId, SharedEntity $required, $table, $reqEntityName)
    {
        $sql = 'INSERT INTO ' . $table . ' VALUES (:entityId,:requiredId)';

        $connection = $this->_em->getConnection();
        try {
            $connection->executeQuery(
                $sql,
                array(
                    'entityId'   => $entityId,
                    'requiredId' => $required->getId(),
                )
            );
        } catch (DBALException $e) {
            throw new EntityAlreadyExistsException("Required " . $reqEntityName);
        }
    }

    /**
     * Delete a requires resource
     *
     * @param int          $entityId
     * @param SharedEntity $required
     * @param string       $table
     * @param string       $entityIdField
     * @param string       $requiredIdField
     *
     * @throws EntityDeletionException
     */
    protected function deleteRequired(
        $entityId,
        SharedEntity $required,
        $table,
        $entityIdField,
        $requiredIdField
    )
    {
        $sql = 'DELETE FROM ' . $table . ' AS erq WHERE erq.' .
            $entityIdField . ' = :entityId AND erq.' .
            $requiredIdField . ' = :requiredId';

        $connection = $this->_em->getConnection();
        $stmt = $connection->executeQuery(
            $sql,
            array(
                'entityId'   => $entityId,
                'requiredId' => $required->getId(),
            )
        );

        if ($stmt->rowCount() != 1) {
            throw new EntityDeletionException();
        }
    }
}
