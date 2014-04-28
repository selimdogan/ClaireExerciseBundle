<?php

namespace SimpleIT\ExerciseBundle\Service\DomainKnowledge;

use JMS\Serializer\SerializationContext;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ApiResourcesBundle\Exercise\MetadataResource;
use SimpleIT\CoreBundle\Services\TransactionalService;
use SimpleIT\ExerciseBundle\Entity\DomainKnowledge\Metadata;
use SimpleIT\ExerciseBundle\Repository\DomainKnowledge\MetadataRepository;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\CoreBundle\Annotation\Transactional;

/**
 * Service which manages the knowledge metadata
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
     * @var OwnerKnowledgeServiceInterface
     */
    private $ownerKnowledgeService;

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
     * Set ownerKnowledgeService
     *
     * @param OwnerKnowledgeServiceInterface $ownerKnowledgeService
     */
    public function setOwnerKnowledgeService($ownerKnowledgeService)
    {
        $this->ownerKnowledgeService = $ownerKnowledgeService;
    }

    /**
     * Find a metadata by owner knowledgeId and metakey
     *
     * @param int $ownerKnowledgeId
     * @param int $metakey
     *
     * @return Metadata
     */
    public function getByOwnerKnowledge($ownerKnowledgeId, $metakey)
    {
        return $this->metadataRepository->find(
            array(
                'ownerKnowledge' => $ownerKnowledgeId,
                'key'            => $metakey
            )
        );
    }

    /**
     * Get all the metadata
     *
     * @param CollectionInformation $collectionInformation
     * @param int                   $ownerKnowledgeId
     *
     * @return array
     */
    public function getAll(
        CollectionInformation $collectionInformation,
        $ownerKnowledgeId = null
    )
    {
        $ownerKnowledge = null;
        if (!is_null($ownerKnowledgeId)) {
            $ownerKnowledge = $this->ownerKnowledgeService->get($ownerKnowledgeId);
        }

        return $this->metadataRepository->findAllBy(
            $collectionInformation,
            $ownerKnowledge
        );
    }

    /**
     * Add a metadata to an owner knowledge
     *
     * @param mixed    $ownerKnowledgeId
     * @param Metadata $metadata
     *
     * @return Metadata
     * @Transactional
     */
    public function addToOwnerKnowledge($ownerKnowledgeId, Metadata $metadata)
    {
        $ownerKnowledge = $this->ownerKnowledgeService->get($ownerKnowledgeId);
        $metadata->setOwnerKnowledge($ownerKnowledge);

        return $this->metadataRepository->insert($metadata);
    }

    /**
     * Save a metadata from an ownerKnowledge
     *
     * @param mixed            $ownerKnowledgeId
     * @param MetadataResource $metadata
     * @param string           $metadataKey
     *
     * @return Metadata
     * @Transactional
     */
    public function saveFromOwnerKnowledge(
        $ownerKnowledgeId,
        MetadataResource $metadata,
        $metadataKey
    )
    {
        $mdToUpdate = $this->getByOwnerKnowledge($ownerKnowledgeId, $metadataKey);
        $mdToUpdate->setValue($metadata->getValue());

        return $this->metadataRepository->update($mdToUpdate);
    }

    /**
     * Remove a metadata from an owner knowledge
     *
     * @param mixed $ownerKnowledgeId
     * @param mixed $metadataKey
     *
     * @Transactional
     */
    public function removeFromOwnerKnowledge($ownerKnowledgeId, $metadataKey)
    {
        $metadata = $this->getByOwnerKnowledge($ownerKnowledgeId, $metadataKey);

        $this->metadataRepository->delete($metadata);
    }

    /**
     * Delete all the metadata for an owner knowledge
     *
     * @param int $ownerKnowledgeId
     */
    public function deleteAllByOwnerKnowledge($ownerKnowledgeId)
    {
        $this->metadataRepository->deleteAllByOwnerKnowledge($ownerKnowledgeId);
    }
}
