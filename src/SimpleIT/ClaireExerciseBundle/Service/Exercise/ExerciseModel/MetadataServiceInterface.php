<?php

namespace SimpleIT\ClaireExerciseBundle\Service\Exercise\ExerciseModel;

use SimpleIT\ClaireExerciseBundle\Model\Resources\MetadataResource;
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
     * @param int $exerciseModelId
     * @param int $metakey
     *
     * @return Metadata
     */
    public function getByExerciseModel($exerciseModelId, $metakey);

    /**
     * Get all the attempts
     *
     * @param CollectionInformation $collectionInformation
     * @param int                   $exerciseModelId
     *
     * @return array
     */
    public function getAll(
        $collectionInformation = null,
        $exerciseModelId = null
    );

    /**
     * Add a metadata to an owner Resource
     *
     * @param mixed    $exerciseModelId
     * @param Metadata $metadata
     *
     * @return Metadata
     */
    public function addToExerciseModel($exerciseModelId, Metadata $metadata);

    /**
     * Save a metadata from an exerciseModel
     *
     * @param mixed            $exerciseModelId
     * @param MetadataResource $metadata
     * @param string           $metadataKey
     *
     * @return Metadata
     */
    public function saveFromExerciseModel(
        $exerciseModelId,
        MetadataResource $metadata,
        $metadataKey
    );

    /**
     * Remove a metadata from a course
     *
     * @param mixed $exerciseModelId
     * @param mixed $metadataKey
     */
    public function removeFromExerciseModel($exerciseModelId, $metadataKey);

    /**
     * Delete all the metadata for an owner resource
     *
     * @param int $exerciseModelId
     */
    public function deleteAllByExerciseModel($exerciseModelId);
}
