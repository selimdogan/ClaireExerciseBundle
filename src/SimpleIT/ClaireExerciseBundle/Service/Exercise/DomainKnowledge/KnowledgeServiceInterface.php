<?php

namespace SimpleIT\ClaireExerciseBundle\Service\Exercise\DomainKnowledge;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\SerializationContext;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge\Knowledge;
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
     * Get an entity
     *
     * @param int $entityId
     *
     * @return Knowledge
     * @throws NonExistingObjectException
     */
    public function get($entityId);

    /**
     * Create an entity from a resource
     *
     * @param KnowledgeResource $resource
     *
     * @return Knowledge
     */
    public function createFromResource($resource);

    /**
     * Create and add an entity from a resource
     *
     * @param KnowledgeResource $resource
     *
     * @return Knowledge
     */
    public function createAndAdd(
        $resource
    );

    /**
     * Add an entity
     *
     * @param Knowledge $entity
     *
     * @return Knowledge
     */
    public function add(
        $entity
    );

    /**
     * Update an entity object from a Resource
     *
     * @param KnowledgeResource $resource
     * @param Knowledge         $entity
     *
     * @return Knowledge
     */
    public function updateFromResource(
        $resource,
        $entity
    );

    /**
     * Save an entity given in form of a Resource
     *
     * @param KnowledgeResource $resource
     * @param int               $entityId
     *
     * @return Knowledge
     */
    public function edit(
        $resource,
        $entityId
    );

    /**
     * Save an entity
     *
     * @param Knowledge $entity
     *
     * @return Knowledge
     */
    public function save($entity);

    /**
     * Get an entity by id and by owner
     *
     * @param int $entityId
     * @param int $ownerId
     *
     * @return Knowledge
     */
    public function getByIdAndOwner($entityId, $ownerId);

    /**
     * Add a requiredKnowledge to a knowledge
     *
     * @param $knowledgeId
     * @param $regKnoId
     *
     * @return Knowledge
     */
    public function addRequiredKnowledge(
        $knowledgeId,
        $regKnoId
    );

    /**
     * Delete a required knowledge
     *
     * @param $knowledgeId
     * @param $reqKnoId
     *
     * @return Knowledge
     */
    public function deleteRequiredResource(
        $knowledgeId,
        $reqKnoId
    );

    /**
     * Edit the required knowledges
     *
     * @param int             $knowledgeId
     * @param ArrayCollection $requiredKnowledges
     *
     * @return Knowledge
     */
    public function editRequiredKnowledges($knowledgeId, ArrayCollection $requiredKnowledges);
}
