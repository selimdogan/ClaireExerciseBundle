<?php

namespace SimpleIT\ClaireExerciseBundle\Repository\Exercise\ExerciseModel;

use Doctrine\ORM\QueryBuilder;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\CoreBundle\Repository\BaseRepository;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\Metadata;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\OwnerExerciseModel;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatorInterface;
use SimpleIT\Utils\Collection\Sort;

/**
 * ExerciseModel repository
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
     * @param OwnerExerciseModel    $ownerExerciseModel
     *
     * @return PaginatorInterface
     */
    public function findAllBy(
        $collectionInformation = null,
        $ownerExerciseModel = null
    )
    {
        $queryBuilder = $this->createQueryBuilder('m');

        if (!is_null($ownerExerciseModel)) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq(
                    'm.ownerExerciseModel',
                    $ownerExerciseModel->getId()
                )
            );
        }

        // Handle Collection Information
        if (!is_null($collectionInformation)) {
            $sorts = $collectionInformation->getSorts();

            foreach ($sorts as $sort) {
                /** @var Sort $sort */
                switch ($sort->getProperty()) {
                    case 'ownerExerciseModelId':
                        $queryBuilder->addOrderBy('m.ownerExerciseModel', $sort->getOrder());
                        break;
                    case 'key':
                        $queryBuilder->addOrderBy('m.key', $sort->getOrder());
                        break;
                }
            }
            $queryBuilder = $this->setRange($queryBuilder, $collectionInformation);
        } else {
            $queryBuilder->addOrderBy('m.ownerExerciseModel', 'ASC');
            $queryBuilder->addOrderBy('m.key', 'ASC');
        }

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Delete all the metadata for an owner exercise model
     *
     * @param int $ownerExerciseModelId
     */
    public function deleteAllByOwnerExerciseModel($ownerExerciseModelId)
    {
        $sql = 'DELETE FROM claire_exercise_model_metadata AS emm WHERE emm.owner_exercise_model_id = :ownerExerciseModelId';

        $connection = $this->_em->getConnection();
        $connection->executeQuery(
            $sql,
            array('ownerExerciseModelId' => $ownerExerciseModelId)
        );
    }
}
