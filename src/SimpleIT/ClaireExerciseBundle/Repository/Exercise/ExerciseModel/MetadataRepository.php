<?php

namespace SimpleIT\ClaireExerciseBundle\Repository\Exercise\ExerciseModel;

use Doctrine\ORM\QueryBuilder;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\ExerciseModel;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\Metadata;
use SimpleIT\ClaireExerciseBundle\Repository\Exercise\SharedEntity\SharedMetadataRepository;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatorInterface;
use SimpleIT\Utils\Collection\Sort;

/**
 * ExerciseModel repository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class MetadataRepository extends SharedMetadataRepository
{
    const METADATA_TABLE = 'claire_exercise_model_metadata';

    const ENTITY_ID_FIELD_NAME = 'exercise_model_id';

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
     * @param ExerciseModel         $exerciseModel
     *
     * @return PaginatorInterface
     */
    public function findAllBy(
        $collectionInformation = null,
        $exerciseModel = null
    )
    {
        return parent::findAllByEntityName(
            'exerciseModel',
            $collectionInformation,
            $exerciseModel
        );
    }

    /**
     * Delete all the metadata for an exercise model
     *
     * @param int $entityId
     */
    public function deleteAllByEntity($entityId)
    {
        parent::deleteAllByEntityByType(
            $entityId,
            'claire_exercise_model_metadata',
            'exercise_model_id'
        );
    }
}
