<?php

namespace SimpleIT\ExerciseBundle\Service\ExerciseResource;

use JMS\Serializer\SerializationContext;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\CommonResource;
use SimpleIT\ApiResourcesBundle\Exercise\MetadataResource;
use SimpleIT\ApiResourcesBundle\Exercise\ResourceResource;
use SimpleIT\ExerciseBundle\Entity\ExerciseResource\Metadata;
use SimpleIT\ExerciseBundle\Model\Resources\ResourceResourceFactory;
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
     * @param int $ownerResourceId
     * @param int $metakey
     *
     * @return Metadata
     */
    public function getByOwnerResource($ownerResourceId, $metakey);

    /**
     * Get all the attempts
     *
     * @param CollectionInformation $collectionInformation
     * @param int                   $ownerResourceId
     *
     * @return array
     */
    public function getAll(
        CollectionInformation $collectionInformation,
        $ownerResourceId = null
    );

    /**
     * Add a metadata to an owner Resource
     *
     * @param mixed    $ownerResourceId
     * @param Metadata $metadata
     *
     * @return Metadata
     */
    public function addToOwnerResource($ownerResourceId, Metadata $metadata);

    /**
     * Save a metadata from an ownerResource
     *
     * @param mixed            $ownerResourceId
     * @param MetadataResource $metadata
     * @param string           $metadataKey
     *
     * @return Metadata
     */
    public function saveFromOwnerResource(
        $ownerResourceId,
        MetadataResource $metadata,
        $metadataKey
    );

    /**
     * Remove a metadata from a course
     *
     * @param mixed $ownerResourceId
     * @param mixed $metadataKey
     */
    public function removeFromOwnerResource($ownerResourceId, $metadataKey);

    /**
     * Delete all the metadata for an owner resource
     *
     * @param int $ownerResourceId
     */
    public function deleteAllByOwnerResource($ownerResourceId);
}
