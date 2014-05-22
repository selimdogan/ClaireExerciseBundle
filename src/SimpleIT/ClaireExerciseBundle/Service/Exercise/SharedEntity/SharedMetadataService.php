<?php

namespace SimpleIT\ClaireExerciseBundle\Service\Exercise\ExerciseResource;

use JMS\Serializer\SerializationContext;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ClaireExerciseBundle\Repository\Exercise\SharedEntity\SharedMetadataRepository;
use SimpleIT\ClaireExerciseBundle\Service\Exercise\SharedEntity\SharedEntityService;
use SimpleIT\CoreBundle\Annotation\Transactional;
use SimpleIT\ClaireExerciseBundle\Entity\SharedEntity\Metadata;
use SimpleIT\ClaireExerciseBundle\Model\Resources\MetadataResource;
use SimpleIT\ClaireExerciseBundle\Repository\Exercise\ExerciseResource\MetadataRepository;
use SimpleIT\CoreBundle\Services\TransactionalService;
use SimpleIT\Utils\Collection\CollectionInformation;

/**
 * Class SharedMetadataService
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class SharedMetadataService extends TransactionalService
{
    /**
     * @var SharedMetadataRepository
     */
    private $metadataRepository;

    /**
     * @var SharedEntityService
     */
    private $entityService;

    /**
     * Set metadataRepository
     *
     * @param SharedMetadataRepository $metadataRepository
     */
    public function setMetadataRepository($metadataRepository)
    {
        $this->metadataRepository = $metadataRepository;
    }

    /**
     * Set entity service
     *
     * @param ExerciseResourceServiceInterface $entityService
     */
    public function setEntityService($entityService)
    {
        $this->entityService = $entityService;
    }

    /**
     * Find a metadata by entity id and metakey
     *
     * @param int $entityId
     * @param int $metakey
     *
     * @return Metadata
     */
    public function getByEntity($entityId, $metakey)
    {
        return $this->metadataRepository->find(
            array(
                'resource' => $entityId,
                'key'      => $metakey
            )
        );
    }

    /**
     * Get all the metadata
     *
     * @param CollectionInformation $collectionInformation
     * @param int                   $entityId
     *
     * @return array
     */
    public function getAll(
        $collectionInformation = null,
        $entityId = null
    )
    {
        $entity = null;
        if (!is_null($entityId)) {
            $entity = $this->entityService->get($entityId);
        }

        return $this->metadataRepository->findAll(
            $collectionInformation,
            $entity
        );
    }

    /**
     * Add a metadata to an entity
     *
     * @param int    $entityId
     * @param Metadata $metadata
     *
     * @return Metadata
     * @Transactional
     */
    public function addToEntity($entityId, Metadata $metadata)
    {
        $resource = $this->entityService->get($entityId);
        $metadata->setEntity($resource);

        return $this->metadataRepository->insert($metadata);
    }

    /**
     * Save a metadata from an entity
     *
     * @param mixed            $entityId
     * @param MetadataResource $metadata
     * @param string           $metadataKey
     *
     * @return Metadata
     * @Transactional
     */
    public function saveFromEntity(
        $entityId,
        MetadataResource $metadata,
        $metadataKey
    )
    {
        $mdToUpdate = $this->getByEntity($entityId, $metadataKey);
        $mdToUpdate->setValue($metadata->getValue());

        return $this->metadataRepository->update($mdToUpdate);
    }

    /**
     * Remove a metadata from an entity
     *
     * @param mixed $entityId
     * @param mixed $metadataKey
     *
     * @Transactional
     */
    public function removeFromEntity($entityId, $metadataKey)
    {
        $metadata = $this->getByEntity($entityId, $metadataKey);

        $this->metadataRepository->delete($metadata);
    }

    /**
     * Delete all the metadata for an owner resource
     *
     * @param int $exerciseModelId
     */
    public function deleteAllByEntity($exerciseModelId)
    {
        $this->metadataRepository->deleteAllByEntity($exerciseModelId);
    }
}
