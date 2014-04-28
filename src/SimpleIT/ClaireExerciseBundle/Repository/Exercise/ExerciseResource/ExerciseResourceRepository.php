<?php

namespace SimpleIT\ClaireExerciseBundle\Repository\Exercise\ExerciseResource;

use Doctrine\DBAL\DBALException;
use Doctrine\ORM\QueryBuilder;
use SimpleIT\ClaireExerciseBundle\Entity\User\User;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\CoreBundle\Model\Paginator;
use SimpleIT\CoreBundle\Repository\BaseRepository;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseResource\ExerciseResource;
use SimpleIT\ClaireExerciseBundle\Exception\EntityAlreadyExistsException;
use SimpleIT\ClaireExerciseBundle\Exception\EntityDeletionException;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatorInterface;
use SimpleIT\Utils\Collection\Sort;

/**
 * ExerciseResource repository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ExerciseResourceRepository extends BaseRepository
{
    /**
     * Find a resource by id
     *
     * @param mixed $resourceId
     *
     * @return ExerciseResource
     * @throws NonExistingObjectException
     */
    public function find($resourceId)
    {
        $resource = parent::find($resourceId);
        if ($resource === null) {
            throw new NonExistingObjectException();
        }

        return $resource;
    }

    /**
     * Return all the resources
     *
     * @param CollectionInformation $collectionInformation
     * @param User                  $author
     *
     * @return PaginatorInterface
     */
    public function findAllBy(
        $collectionInformation,
        $author
    )
    {
        $queryBuilder = $this->createQueryBuilder('r');

        if (!is_null($author)) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq(
                    'r.author',
                    $author->getId()
                )
            );
        }

        // Handle Collection Information
        if (!is_null($collectionInformation)) {
            $filters = $collectionInformation->getFilters();
            foreach ($filters as $filter => $value) {
                switch ($filter) {
                    case 'authorId':
                        $queryBuilder->andWhere(
                            $queryBuilder->expr()->eq(
                                'r.author',
                                $value
                            )
                        );
                        break;
                    case 'id':
                        $queryBuilder->andWhere(
                            $queryBuilder->expr()->eq(
                                'r.id',
                                $value
                            )
                        );
                        break;
                    case 'type':
                        $queryBuilder->andWhere(
                            $queryBuilder->expr()->eq(
                                'r.type',
                                $value
                            )
                        );
                        break;
                    default:
                        break;
                }
            }
            $sorts = $collectionInformation->getSorts();

            foreach ($sorts as $sort) {
                /** @var Sort $sort */
                switch ($sort->getProperty()) {
                    case 'authorId':
                        $queryBuilder->addOrderBy('r.author', $sort->getOrder());
                        break;
                    case 'type':
                        $queryBuilder->addOrderBy('r.type', $sort->getOrder());
                        break;
                    case 'id':
                        $queryBuilder->addOrderBy('r.id', $sort->getOrder());
                        break;
                }
            }
            $queryBuilder = $this->setRange($queryBuilder, $collectionInformation);
        } else {
            $queryBuilder->addOrderBy('r.id');
        }

        return new Paginator($queryBuilder);
    }

    /**
     * Add a required resource to a resource
     *
     * @param int              $resourceId
     * @param ExerciseResource $requiredResource
     *
     * @throws EntityAlreadyExistsException
     */
    public function addRequiredResource($resourceId, ExerciseResource $requiredResource)
    {
        $sql = 'INSERT INTO claire_exercise_resource_resource_requirement VALUES (:resourceId,:requiredId)';

        $connection = $this->_em->getConnection();
        try {
            $connection->executeQuery(
                $sql,
                array(
                    'resourceId' => $resourceId,
                    'requiredId' => $requiredResource->getId(),
                )
            );
        } catch (DBALException $e) {
            throw new EntityAlreadyExistsException("Required resource");
        }
    }

    /**
     * Delete a requires resource
     *
     * @param int              $resourceId
     * @param ExerciseResource $requiredResource
     *
     * @throws EntityDeletionException
     */
    public function deleteRequiredResource($resourceId, ExerciseResource $requiredResource)
    {
        $sql = 'DELETE FROM claire_exercise_resource_resource_requirement AS rrq WHERE rrq.resource_id = :resourceId AND rrq.required_id = :requiredId';

        $connection = $this->_em->getConnection();
        $stmt = $connection->executeQuery(
            $sql,
            array(
                'resourceId' => $resourceId,
                'requiredId' => $requiredResource->getId(),
            )
        );

        if ($stmt->rowCount() != 1) {
            throw new EntityDeletionException();
        }
    }
}
