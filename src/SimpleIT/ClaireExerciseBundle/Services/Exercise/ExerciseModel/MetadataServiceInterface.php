<?php

namespace SimpleIT\ClaireExerciseBundle\Service\ExerciseModel;

use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\MetadataResource;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\Metadata;
use SimpleIT\Utils\Collection\CollectionInformation;

/**
 * Interface for Service which manages the exercise models
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
interface MetadataServiceInterface
{
    /**
     * Find a metadata by resourceId and metakey
     *
     * @param int $ownerExerciseModelId
     * @param int $metakey
     *
     * @return Metadata
     */
    public function getByOwnerExerciseModel($ownerExerciseModelId, $metakey);

    /**
     * Get all the attempts
     *
     * @param CollectionInformation $collectionInformation
     * @param int                   $ownerExerciseModelId
     *
     * @return array
     */
    public function getAll(
        CollectionInformation $collectionInformation,
        $ownerExerciseModelId = null
    );

    /**
     * Add a metadata to an owner Resource
     *
     * @param mixed    $ownerExerciseModelId
     * @param Metadata $metadata
     *
     * @return Metadata
     */
    public function addToOwnerExerciseModel($ownerExerciseModelId, Metadata $metadata);

    /**
     * Save a metadata from an ownerExerciseModel
     *
     * @param mixed            $ownerExerciseModelId
     * @param MetadataResource $metadata
     * @param string           $metadataKey
     *
     * @return Metadata
     */
    public function saveFromOwnerExerciseModel(
        $ownerExerciseModelId,
        MetadataResource $metadata,
        $metadataKey
    );

    /**
     * Remove a metadata from a course
     *
     * @param mixed $ownerExerciseModelId
     * @param mixed $metadataKey
     */
    public function removeFromOwnerExerciseModel($ownerExerciseModelId, $metadataKey);

    /**
     * Delete all the metadata for an owner resource
     *
     * @param int $ownerExerciseModelId
     */
    public function deleteAllByOwnerExerciseModel($ownerExerciseModelId);
}
