<?php

namespace SimpleIT\ClaireExerciseBundle\Service\Exercise\ExerciseResource;

use JMS\Serializer\SerializationContext;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\CoreBundle\Annotation\Transactional;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseResource\Metadata;
use SimpleIT\ClaireExerciseBundle\Model\Resources\MetadataResource;
use SimpleIT\ClaireExerciseBundle\Repository\Exercise\ExerciseResource\MetadataRepository;
use SimpleIT\CoreBundle\Services\TransactionalService;
use SimpleIT\Utils\Collection\CollectionInformation;

/**
 * Service which manages the exercise resources
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class MetadataService extends TransactionalService implements MetadataServiceInterface
{
    /**
     * @var MetadataRepository
     */
    private $metadataRepository;

    /**
     * @var ExerciseResourceServiceInterface
     */
    private $resourceService;

    /**
     * Set metadataRepository
     *
     * @param MetadataRepository $metadataRepository
     */
    public function setMetadataRepository($metadataRepository)
    {
        $this->metadataRepository = $metadataRepository;
    }

    /**
     * Set resourceService
     *
     * @param ExerciseResourceServiceInterface $resourceService
     */
    public function setResourceService($resourceService)
    {
        $this->resourceService = $resourceService;
    }

    /**
     * Find a metadata by resourceId and metakey
     *
     * @param int $resourceId
     * @param int $metakey
     *
     * @return Metadata
     */
    public function getByExerciseResource($resourceId, $metakey)
    {
        return $this->metadataRepository->find(
            array(
                'resource' => $resourceId,
                'key'      => $metakey
            )
        );
    }

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
    )
    {
        $resource = null;
        if (!is_null($resourceId)) {
            $resource = $this->resourceService->get($resourceId);
        }

        return $this->metadataRepository->findAllBy(
            $collectionInformation,
            $resource
        );
    }

    /**
     * Add a metadata to an owner Resource
     *
     * @param mixed    $resourceId
     * @param Metadata $metadata
     *
     * @return Metadata
     * @Transactional
     */
    public function addToExerciseResource($resourceId, Metadata $metadata)
    {
        $resource = $this->resourceService->get($resourceId);
        $metadata->setResource($resource);

        return $this->metadataRepository->insert($metadata);
    }

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
    )
    {
        $mdToUpdate = $this->getByExerciseResource($resourceId, $metadataKey);
        $mdToUpdate->setValue($metadata->getValue());

        return $this->metadataRepository->update($mdToUpdate);
    }

    /**
     * Remove a metadata from an owner resource
     *
     * @param mixed $resourceId
     * @param mixed $metadataKey
     *
     * @Transactional
     */
    public function removeFromExerciseResource($resourceId, $metadataKey)
    {
        $metadata = $this->getByExerciseResource($resourceId, $metadataKey);

        $this->metadataRepository->delete($metadata);
    }

    /**
     * Delete all the metadata for an owner resource
     *
     * @param int $resourceId
     */
    public function deleteAllByExerciseResource($resourceId)
    {
        $this->metadataRepository->deleteAllByResource($resourceId);
    }
}
