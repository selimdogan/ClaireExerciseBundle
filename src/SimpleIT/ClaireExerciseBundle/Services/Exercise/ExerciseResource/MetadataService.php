<?php

namespace SimpleIT\ClaireExerciseBundle\Service\ExerciseResource;

use JMS\Serializer\SerializationContext;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\MetadataResource;
use SimpleIT\CoreBundle\Services\TransactionalService;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseResource\Metadata;
use SimpleIT\ClaireExerciseBundle\Repository\Exercise\ExerciseResource\MetadataRepository;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\CoreBundle\Annotation\Transactional;

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
     * @var OwnerResourceServiceInterface
     */
    private $ownerResourceService;

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
     * Set ownerResourceService
     *
     * @param OwnerResourceServiceInterface $ownerResourceService
     */
    public function setOwnerResourceService($ownerResourceService)
    {
        $this->ownerResourceService = $ownerResourceService;
    }

    /**
     * Find a metadata by resourceId and metakey
     *
     * @param int $ownerResourceId
     * @param int $metakey
     *
     * @return Metadata
     */
    public function getByOwnerResource($ownerResourceId, $metakey)
    {
        return $this->metadataRepository->find(
            array(
                'ownerResource' => $ownerResourceId,
                'key'           => $metakey
            )
        );
    }

    /**
     * Get all the metadata
     *
     * @param CollectionInformation $collectionInformation
     * @param int                   $ownerResourceId
     *
     * @return array
     */
    public function getAll(
        CollectionInformation $collectionInformation,
        $ownerResourceId = null
    )
    {
        $ownerResource = null;
        if (!is_null($ownerResourceId)) {
            $ownerResource = $this->ownerResourceService->get($ownerResourceId);
        }

        return $this->metadataRepository->findAllBy(
            $collectionInformation,
            $ownerResource
        );
    }

    /**
     * Add a metadata to an owner Resource
     *
     * @param mixed    $ownerResourceId
     * @param Metadata $metadata
     *
     * @return Metadata
     * @Transactional
     */
    public function addToOwnerResource($ownerResourceId, Metadata $metadata)
    {
        $ownerResource = $this->ownerResourceService->get($ownerResourceId);
        $metadata->setOwnerResource($ownerResource);

        return $this->metadataRepository->insert($metadata);
    }

    /**
     * Save a metadata from an ownerResource
     *
     * @param mixed            $ownerResourceId
     * @param MetadataResource $metadata
     * @param string           $metadataKey
     *
     * @return Metadata
     * @Transactional
     */
    public function saveFromOwnerResource(
        $ownerResourceId,
        MetadataResource $metadata,
        $metadataKey
    )
    {
        $mdToUpdate = $this->getByOwnerResource($ownerResourceId, $metadataKey);
        $mdToUpdate->setValue($metadata->getValue());

        return $this->metadataRepository->update($mdToUpdate);
    }

    /**
     * Remove a metadata from an owner resource
     *
     * @param mixed $ownerResourceId
     * @param mixed $metadataKey
     *
     * @Transactional
     */
    public function removeFromOwnerResource($ownerResourceId, $metadataKey)
    {
        $metadata = $this->getByOwnerResource($ownerResourceId, $metadataKey);

        $this->metadataRepository->delete($metadata);
    }

    /**
     * Delete all the metadata for an owner resource
     *
     * @param int $ownerResourceId
     */
    public function deleteAllByOwnerResource($ownerResourceId)
    {
        $this->metadataRepository->deleteAllByOwnerResource($ownerResourceId);
    }
}
