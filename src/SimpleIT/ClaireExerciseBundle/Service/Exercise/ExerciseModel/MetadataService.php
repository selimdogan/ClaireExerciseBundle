<?php

namespace SimpleIT\ClaireExerciseBundle\Service\Exercise\ExerciseModel;

use SimpleIT\ClaireExerciseBundle\Model\Resources\MetadataResource;
use SimpleIT\CoreBundle\Services\TransactionalService;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\Metadata;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ResourceResourceFactory;
use SimpleIT\ClaireExerciseBundle\Repository\Exercise\ExerciseModel\MetadataRepository;
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
     * @var ExerciseModelServiceInterface
     */
    private $exerciseModelService;

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
     * Set exerciseModelService
     *
     * @param ExerciseModelServiceInterface $exerciseModelService
     */
    public function setExerciseModelService($exerciseModelService)
    {
        $this->exerciseModelService = $exerciseModelService;
    }

    /**
     * Find a metadata by resourceId and metakey
     *
     * @param int $exerciseModelId
     * @param int $metakey
     *
     * @return Metadata
     */
    public function getByExerciseModel($exerciseModelId, $metakey)
    {
        return $this->metadataRepository->find(
            array(
                'exerciseModel' => $exerciseModelId,
                'key'                => $metakey
            )
        );
    }

    /**
     * Get all the metadata
     *
     * @param CollectionInformation $collectionInformation
     * @param int                   $exerciseModelId
     *
     * @return array
     */
    public function getAll(
        $collectionInformation = null,
        $exerciseModelId = null
    )
    {
        $exerciseModel = null;
        if (!is_null($exerciseModelId)) {
            $exerciseModel = $this->exerciseModelService->get($exerciseModelId);
        }

        return $this->metadataRepository->findAllBy(
            $collectionInformation,
            $exerciseModel
        );
    }

    /**
     * Add a metadata to an owner Resource
     *
     * @param mixed    $exerciseModelId
     * @param Metadata $metadata
     *
     * @return Metadata
     * @Transactional
     */
    public function addToExerciseModel($exerciseModelId, Metadata $metadata)
    {
        $exerciseModel = $this->exerciseModelService->get($exerciseModelId);
        $metadata->setExerciseModel($exerciseModel);

        return $this->metadataRepository->insert($metadata);
    }

    /**
     * Save a metadata from an exerciseModel
     *
     * @param mixed            $exerciseModelId
     * @param MetadataResource $metadata
     * @param string           $metadataKey
     *
     * @return Metadata
     * @Transactional
     */
    public function saveFromExerciseModel(
        $exerciseModelId,
        MetadataResource $metadata,
        $metadataKey
    )
    {
        $mdToUpdate = $this->getByExerciseModel($exerciseModelId, $metadataKey);
        $mdToUpdate->setValue($metadata->getValue());

        return $this->metadataRepository->update($mdToUpdate);
    }

    /**
     * Remove a metadata from an owner exercise model
     *
     * @param mixed $exerciseModelId
     * @param mixed $metadataKey
     *
     * @Transactional
     */
    public function removeFromExerciseModel($exerciseModelId, $metadataKey)
    {
        $metadata = $this->getByExerciseModel($exerciseModelId, $metadataKey);

        $this->metadataRepository->delete($metadata);
    }

    /**
     * Delete all the metadata for an owner resource
     *
     * @param int $exerciseModelId
     */
    public function deleteAllByExerciseModel($exerciseModelId)
    {
        $this->metadataRepository->deleteAllByExerciseModel($exerciseModelId);
    }
}
