<?php

namespace SimpleIT\ClaireExerciseBundle\Repository\Exercise\SharedEntity;

use Doctrine\ORM\QueryBuilder;
use SimpleIT\ClaireExerciseBundle\Entity\SharedEntity\SharedEntity;
use SimpleIT\CoreBundle\Repository\BaseRepository;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatorInterface;
use SimpleIT\Utils\Collection\Sort;

/**
 * SharedMetadata repository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class SharedMetadataRepository extends BaseRepository
{
    /**
     * Return all the metadata
     *
     * @param string                $entityName
     * @param CollectionInformation $collectionInformation
     * @param SharedEntity          $entity
     *
     * @return PaginatorInterface
     */
    public function findAllByEntityName(
        $entityName,
        $collectionInformation = null,
        $entity = null
    )
    {
        $queryBuilder = $this->createQueryBuilder('m');

        if (!is_null($entity)) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq(
                    'm.' . $entityName,
                    $entity->getId()
                )
            );
        }

        // Handle Collection Information
        if (!is_null($collectionInformation)) {
            $sorts = $collectionInformation->getSorts();

            foreach ($sorts as $sort) {
                /** @var Sort $sort */
                switch ($sort->getProperty()) {
                    case $entityName:
                        $queryBuilder->addOrderBy('m.' . $entityName, $sort->getOrder());
                        break;
                    case 'key':
                        $queryBuilder->addOrderBy('m.key', $sort->getOrder());
                        break;
                }
            }
            $queryBuilder = $this->setRange($queryBuilder, $collectionInformation);
        } else {
            $queryBuilder->addOrderBy('m.' . $entityName, 'ASC');
            $queryBuilder->addOrderBy('m.key', 'ASC');
        }

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Delete all the metadata for an exercise model
     *
     * @param int    $entityId
     * @param string $table
     * @param string $entityIdFieldName
     */
    public function deleteAllByEntityByType($entityId, $table, $entityIdFieldName)
    {
        $sql = 'DELETE FROM ' . $table . ' AS emm WHERE emm.' .
            $entityIdFieldName . '= :entityId';

        $connection = $this->_em->getConnection();
        $connection->executeQuery(
            $sql,
            array('entityId' => $entityId)
        );
    }

    /**
     * Delete all the metadata for an entity
     *
     * @param int $entityId
     */
    abstract public function deleteAllByEntity($entityId);
}
