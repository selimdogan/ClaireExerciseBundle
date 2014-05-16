<?php

namespace SimpleIT\ClaireExerciseBundle\Service\Exercise\DomainKnowledge;

use JMS\Serializer\SerializationContext;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\MetadataResource;
use SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge\Metadata;
use SimpleIT\Utils\Collection\CollectionInformation;

/**
 * Interface for service which manages the knowledge metadata
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
interface MetadataServiceInterface
{
    /**
     * Find a metadata by owner knowledgeId and metakey
     *
     * @param int $ownerKnowledgeId
     * @param int $metakey
     *
     * @return Metadata
     */
    public function getByOwnerKnowledge($ownerKnowledgeId, $metakey);

    /**
     * Get all the metadata
     *
     * @param CollectionInformation $collectionInformation
     * @param int                   $ownerKnowledgeId
     *
     * @return array
     */
    public function getAll(
        $collectionInformation = null,
        $ownerKnowledgeId = null
    );

    /**
     * Add a metadata to an owner knowledge
     *
     * @param mixed    $ownerKnowledgeId
     * @param Metadata $metadata
     *
     * @return Metadata
     */
    public function addToOwnerKnowledge($ownerKnowledgeId, Metadata $metadata);

    /**
     * Save a metadata from an ownerKnowledge
     *
     * @param mixed            $ownerKnowledgeId
     * @param MetadataResource $metadata
     * @param string           $metadataKey
     *
     * @return Metadata
     */
    public function saveFromOwnerKnowledge(
        $ownerKnowledgeId,
        MetadataResource $metadata,
        $metadataKey
    );

    /**
     * Remove a metadata from an owner knowledge
     *
     * @param mixed $ownerKnowledgeId
     * @param mixed $metadataKey
     */
    public function removeFromOwnerKnowledge($ownerKnowledgeId, $metadataKey);

    /**
     * Delete all the metadata for an owner knowledge
     *
     * @param int $ownerKnowledgeId
     */
    public function deleteAllByOwnerKnowledge($ownerKnowledgeId);
}
