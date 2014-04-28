<?php

namespace SimpleIT\ExerciseBundle\Repository\ExerciseResource;

use Doctrine\ORM\QueryBuilder;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\CoreBundle\Repository\BaseRepository;
use SimpleIT\ExerciseBundle\Entity\ExerciseResource\Metadata;
use SimpleIT\ExerciseBundle\Entity\ExerciseResource\OwnerResource;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatorInterface;
use SimpleIT\Utils\Collection\Sort;

/**
 * Resource Metadata repository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class MetadataRepository extends BaseRepository
{
    /**
     * Find a model by id
     *
     * @param array $parameters
     *
     * @return Metadata
     * @throws NonExistingObjectException
     */
    public function find($parameters)
    {
        $metadata = parent::find($parameters);
        if ($metadata === null) {
            throw new NonExistingObjectException();
        }

        return $metadata;
    }

    /**
     * Return all the metadata
     *
     * @param CollectionInformation $collectionInformation
     * @param OwnerResource         $ownerResource
     *
     * @return PaginatorInterface
     */
    public function findAllBy(
        CollectionInformation $collectionInformation,
        $ownerResource
    )
    {
        $queryBuilder = $this->createQueryBuilder('m');

        if (!is_null($ownerResource)) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq(
                    'm.ownerResource',
                    $ownerResource->getId()
                )
            );
        }

        // Handle Collection Information
        if (!is_null($collectionInformation)) {
            $sorts = $collectionInformation->getSorts();

            foreach ($sorts as $sort) {
                /** @var Sort $sort */
                switch ($sort->getProperty()) {
                    case 'ownerResourceId':
                        $queryBuilder->addOrderBy('m.ownerResource', $sort->getOrder());
                        break;
                    case 'key':
                        $queryBuilder->addOrderBy('m.key', $sort->getOrder());
                        break;
                }
            }
            $queryBuilder = $this->setRange($queryBuilder, $collectionInformation);
        } else {
            $queryBuilder->addOrderBy('m.ownerResource', 'ASC');
            $queryBuilder->addOrderBy('m.key', 'ASC');
        }

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Delete all the metadata for an owner resource
     *
     * @param int $ownerResourceId
     */
    public function deleteAllByOwnerResource($ownerResourceId)
    {
        $sql = 'DELETE FROM claire_exercise_resource_metadata AS rm WHERE rm.owner_resource_id = :ownerResourceId';

        $connection = $this->_em->getConnection();
        $connection->executeQuery(
            $sql,
            array('ownerResourceId' => $ownerResourceId)
        );
    }
}
