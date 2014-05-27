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
     * Get a knowledge
     *
     * @param int $knowledgeId
     *
     * @return Knowledge
     * @throws NonExistingObjectException
     */
    public function get($knowledgeId);

    /**
     * Create a knowledge from a resource
     *
     * @param KnowledgeResource $resource
     *
     * @return Knowledge
     */
    public function createFromResource($resource);

    /**
     * Create and add a knowledge from a resource
     *
     * @param KnowledgeResource $resource
     *
     * @return Knowledge
     */
    public function createAndAdd(
        $resource
    );

    /**
     * Add a knowledge
     *
     * @param Knowledge $knowledge
     *
     * @return Knowledge
     */
    public function add(
        $knowledge
    );

    /**
     * Update a knowledge object from a Resource
     *
     * @param KnowledgeResource $resource
     * @param Knowledge         $knowledge
     *
     * @return Knowledge
     */
    public function updateFromResource(
        $resource,
        $knowledge
    );

    /**
     * Save a knowledge given in form of a Resource
     *
     * @param KnowledgeResource $resource
     *
     * @return Knowledge
     */
    public function edit(
        $resource
    );

    /**
     * Save a knowledge
     *
     * @param Knowledge $knowledge
     *
     * @return Knowledge
     */
    public function save($knowledge);

    /**
     * Get a knowledge by id and by owner
     *
     * @param int $knowledgeId
     * @param int $ownerId
     *
     * @return Knowledge
     */
    public function getByIdAndOwner($knowledgeId, $ownerId);

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
