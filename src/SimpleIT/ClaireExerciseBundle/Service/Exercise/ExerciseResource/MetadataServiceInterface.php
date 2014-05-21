<?php

namespace SimpleIT\ClaireExerciseBundle\Service\Exercise\ExerciseResource;

use JMS\Serializer\SerializationContext;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseResource\Metadata;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\CommonResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\MetadataResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ResourceResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ResourceResourceFactory;
use SimpleIT\Utils\Collection\CollectionInformation;

/**
 * Interface for Service which manages the exercise resources metadata
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
interface MetadataServiceInterface
{
    /**
     * Find a metadata by resourceId and metakey
     *
     * @param int $resourceId
     * @param int $metakey
     *
     * @return Metadata
     */
    public function getByExerciseResource($resourceId, $metakey);

    /**
     * Get all the metadata
     *
     * @param CollectionInformation $collectionInformation
     * @param int                   $resourceId
     *
     * @return array
     */
    public function getAll(
        $collectionInformation = null,
        $resourceId = null
    );

    /**
     * Add a metadata to an owner Resource
     *
     * @param mixed    $resourceId
     * @param Metadata $metadata
     *
     * @return Metadata
     * @Transactional
     */
    public function addToExerciseResource($resourceId, Metadata $metadata);

    /**
     * Save a metadata from an resource
     *
     * @param mixed            $resourceId
     * @param MetadataResource $metadata
     * @param string           $metadataKey
     *
     * @return Metadata
     * @Transactional
     */
    public function saveFromExerciseResource(
        $resourceId,
        MetadataResource $metadata,
        $metadataKey
    );

    /**
     * Remove a metadata from an owner resource
     *
     * @param mixed $resourceId
     * @param mixed $metadataKey
     *
     * @Transactional
     */
    public function removeFromExerciseResource($resourceId, $metadataKey);

    /**
     * Delete all the metadata for an owner resource
     *
     * @param int $resourceId
     */
    public function deleteAllByExerciseResource($resourceId);
}
