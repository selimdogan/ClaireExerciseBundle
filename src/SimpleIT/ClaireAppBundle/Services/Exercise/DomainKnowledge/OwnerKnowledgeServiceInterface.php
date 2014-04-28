<?php

namespace SimpleIT\ExerciseBundle\Service\DomainKnowledge;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use SimpleIT\ApiResourcesBundle\Exercise\OwnerKnowledgeResource;
use SimpleIT\ExerciseBundle\Entity\DomainKnowledge\Knowledge;
use SimpleIT\ExerciseBundle\Entity\DomainKnowledge\OwnerKnowledge;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatorInterface;

/**
 * Interface OwnerKnowledgeServiceInterface
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
interface OwnerKnowledgeServiceInterface
{
    /**
     * Get an Owner Knowledge entity
     *
     * @param int       $ownerKnowledgeId
     * @param Knowledge $knowledge
     *
     * @return OwnerKnowledge
     */
    public function get($ownerKnowledgeId, $knowledge = null);

    /**
     * Get a list of Owner Knowledges
     *
     * @param CollectionInformation $collectionInformation The collection information
     * @param int                   $ownerId
     * @param int                   $knowledgeId
     *
     * @return PaginatorInterface
     */
    public function getAll(
        CollectionInformation $collectionInformation = null,
        $ownerId = null,
        $knowledgeId = null
    );

    /**
     * Get an OwnerKnowledge by id and by owner
     *
     * @param int $knowledgeId
     * @param int $ownerId
     *
     * @return OwnerKnowledge
     */
    public function getByIdAndOwner($knowledgeId, $ownerId);

    /**
     * Get an OwnerKnowledge by id and by knowledge
     *
     * @param int $ownerKnowledgeId
     * @param int $knowledgeId
     *
     * @return OwnerKnowledge
     */
    public function getByIdAndKnowledge($ownerKnowledgeId, $knowledgeId);

    /**
     * Create and add an ownerKnowledge from an ownerKnowledgeResource
     *
     * @param OwnerKnowledgeResource $ownerKnowledgeResource
     * @param int                    $knowledgeId
     * @param int                    $ownerId
     *
     * @return OwnerKnowledge
     */
    public function createAndAdd(
        OwnerKnowledgeResource $ownerKnowledgeResource,
        $knowledgeId,
        $ownerId
    );

    /**
     * Save an owner knowledge given in form of an OwnerKnowledgeResource
     *
     * @param OwnerKnowledgeResource $ownerKnowledgeResource
     * @param int                    $ownerKnowledgeId
     * @param int                    $knowledgeId
     *
     * @return OwnerKnowledge
     */
    public function edit(
        OwnerKnowledgeResource $ownerKnowledgeResource,
        $ownerKnowledgeId,
        $knowledgeId = null
    );

    /**
     * Add an owner knowledge
     *
     * @param OwnerKnowledge $ownerKnowledge
     *
     * @return OwnerKnowledge
     */
    public function add(OwnerKnowledge $ownerKnowledge);

    /**
     * Save an owner knowledge
     *
     * @param OwnerKnowledge $ownerKnowledge
     *
     * @return OwnerKnowledge
     * @Transactional
     */
    public function save(OwnerKnowledge $ownerKnowledge);

    /**
     * Edit all the metadata of a knowledge
     *
     * @param int             $ownerKnowledgeId
     * @param ArrayCollection $metadatas
     *
     * @return Collection
     */
    public function editMetadata($ownerKnowledgeId, ArrayCollection $metadatas);

    /**
     * Delete an owner knowledge
     *
     * @param $ownerKnowledgeId
     */
    public function remove($ownerKnowledgeId);
}
