<?php

namespace SimpleIT\ExerciseBundle\Service\ExerciseModel;

use SimpleIT\ApiResourcesBundle\Exercise\MetadataResource;
use SimpleIT\CoreBundle\Services\TransactionalService;
use SimpleIT\ExerciseBundle\Entity\ExerciseModel\Metadata;
use SimpleIT\ExerciseBundle\Model\Resources\ResourceResourceFactory;
use SimpleIT\ExerciseBundle\Repository\ExerciseModel\MetadataRepository;
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
     * @var OwnerExerciseModelServiceInterface
     */
    private $ownerExerciseModelService;

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
     * Set ownerExerciseModelService
     *
     * @param OwnerExerciseModelServiceInterface $ownerExerciseModelService
     */
    public function setOwnerExerciseModelService($ownerExerciseModelService)
    {
        $this->ownerExerciseModelService = $ownerExerciseModelService;
    }

    /**
     * Find a metadata by resourceId and metakey
     *
     * @param int $ownerExerciseModelId
     * @param int $metakey
     *
     * @return Metadata
     */
    public function getByOwnerExerciseModel($ownerExerciseModelId, $metakey)
    {
        return $this->metadataRepository->find(
            array(
                'ownerExerciseModel' => $ownerExerciseModelId,
                'key'                => $metakey
            )
        );
    }

    /**
     * Get all the metadata
     *
     * @param CollectionInformation $collectionInformation
     * @param int                   $ownerExerciseModelId
     *
     * @return array
     */
    public function getAll(
        CollectionInformation $collectionInformation,
        $ownerExerciseModelId = null
    )
    {
        $ownerExerciseModel = null;
        if (!is_null($ownerExerciseModelId)) {
            $ownerExerciseModel = $this->ownerExerciseModelService->get($ownerExerciseModelId);
        }

        return $this->metadataRepository->findAllBy(
            $collectionInformation,
            $ownerExerciseModel
        );
    }

    /**
     * Add a metadata to an owner Resource
     *
     * @param mixed    $ownerExerciseModelId
     * @param Metadata $metadata
     *
     * @return Metadata
     * @Transactional
     */
    public function addToOwnerExerciseModel($ownerExerciseModelId, Metadata $metadata)
    {
        $ownerExerciseModel = $this->ownerExerciseModelService->get($ownerExerciseModelId);
        $metadata->setOwnerExerciseModel($ownerExerciseModel);

        return $this->metadataRepository->insert($metadata);
    }

    /**
     * Save a metadata from an ownerExerciseModel
     *
     * @param mixed            $ownerExerciseModelId
     * @param MetadataResource $metadata
     * @param string           $metadataKey
     *
     * @return Metadata
     * @Transactional
     */
    public function saveFromOwnerExerciseModel(
        $ownerExerciseModelId,
        MetadataResource $metadata,
        $metadataKey
    )
    {
        $mdToUpdate = $this->getByOwnerExerciseModel($ownerExerciseModelId, $metadataKey);
        $mdToUpdate->setValue($metadata->getValue());

        return $this->metadataRepository->update($mdToUpdate);
    }

    /**
     * Remove a metadata from an owner exercise model
     *
     * @param mixed $ownerExerciseModelId
     * @param mixed $metadataKey
     *
     * @Transactional
     */
    public function removeFromOwnerExerciseModel($ownerExerciseModelId, $metadataKey)
    {
        $metadata = $this->getByOwnerExerciseModel($ownerExerciseModelId, $metadataKey);

        $this->metadataRepository->delete($metadata);
    }

    /**
     * Delete all the metadata for an owner resource
     *
     * @param int $ownerExerciseModelId
     */
    public function deleteAllByOwnerExerciseModel($ownerExerciseModelId)
    {
        $this->metadataRepository->deleteAllByOwnerExerciseModel($ownerExerciseModelId);
    }
}
