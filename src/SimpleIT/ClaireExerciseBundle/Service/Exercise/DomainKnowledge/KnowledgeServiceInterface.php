<?php

namespace SimpleIT\ClaireExerciseBundle\Service\Exercise\DomainKnowledge;

use JMS\Serializer\SerializationContext;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge\Knowledge;
use SimpleIT\ClaireExerciseBundle\Exception\InconsistentEntityException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\DomainKnowledge\CommonKnowledge;
use SimpleIT\ClaireExerciseBundle\Model\Resources\KnowledgeResource;
use SimpleIT\ClaireExerciseBundle\Service\Exercise\SharedEntity\SharedEntityServiceInterface;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;

/**
 * Interface for service which manages the domain knowledge
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
interface KnowledgeServiceInterface extends SharedEntityServiceInterface
{
    /**
     * Get an entity by its id
     *
     * @param int $knowledgeId
     *
     * @return Knowledge
     * @throws NonExistingObjectException
     */
    public function get($knowledgeId);

    /**
     * Get the final parent entity (the one with content) pointed by an entity
     *
     * @param int $entityId
     *
     * @return Knowledge
     * @throws InconsistentEntityException
     */
    public function getParent($entityId);

    /**
     * Create and add an entity from a resource (saving).
     * Required fields: type, title, [content or parent], draft, owner, author, archived, metadata
     * Must be null: id
     *
     * @param KnowledgeResource $knowledgeResource
     *
     * @return Knowledge
     */
    public function createFromResource($knowledgeResource);

    /**
     * Create and add an entity from a resource (saving).
     * Required fields: type, title, [content or parent], draft, owner, author, archived, metadata
     * Must be null: id
     *
     * @param KnowledgeResource $knowledgeResource
     *
     * @return Knowledge
     */
    public function createAndAdd(
        $knowledgeResource
    );

    /**
     * Add a new entity (saving). The id must be null.
     *
     * @param Knowledge $knowledge
     *
     * @return Knowledge
     */
    public function add(
        $knowledge
    );

    /**
     * Update an entity object from a Resource (no saving).
     * Only the fields that are not null in the resource are taken in account to edit the entity.
     * The id of an entity can never be modified (ignored if not null)
     *
     * @param KnowledgeResource $knowledgeResource
     * @param Knowledge         $knowledge
     *
     * @return Knowledge
     */
    public function updateFromResource(
        $knowledgeResource,
        $knowledge
    );

    /**
     * Edit and save an entity given in form of a Resource object.
     * Only the fields that are not null in the resource are taken in account to edit the entity.
     * The id of the entity that must be modified is stored in the field id.
     * The id of an entity can never be modified.
     *
     * @param KnowledgeResource $knowledgeResource
     *
     * @return Knowledge
     */
    public function edit(
        $knowledgeResource
    );

    /**
     * Save an entity after modifications
     *
     * @param Knowledge $knowledge
     *
     * @return Knowledge
     */
    public function save($knowledge);

    /**
     * Get an entity by its id and by the owner id
     *
     * @param int $knowledgeId
     * @param int $ownerId
     *
     * @return Knowledge
     */
    public function getByIdAndOwner($knowledgeId, $ownerId);

    /**
     * Subscribe to an entity: the new entity is a pointer to the parent entity. It has no
     * content and no metadata because these elements rely on the parent.
     *
     * @param int $ownerId        The id of the owner who wants to get the new pointer
     * @param int $parentEntityId The id of the parent entity
     *
     * @return Knowledge
     */
    public function subscribe($ownerId, $parentEntityId);
}
