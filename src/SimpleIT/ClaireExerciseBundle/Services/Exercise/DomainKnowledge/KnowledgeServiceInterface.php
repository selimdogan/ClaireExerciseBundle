<?php

namespace SimpleIT\ClaireExerciseBundle\Service\DomainKnowledge;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\SerializationContext;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\DomainKnowledge\CommonKnowledge;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\KnowledgeResource;
use SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge\Knowledge;
use SimpleIT\ClaireExerciseBundle\Exception\NoAuthorException;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatorInterface;

/**
 * Interface for service which manages the domain knowledge
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
interface KnowledgeServiceInterface
{
    /**
     * Add a knowledge from a knowledgeResource
     *
     * @param Knowledge $knowledge
     *
     * @return Knowledge
     */
    public function add(Knowledge $knowledge);

    /**
     * Create an Knowledge object from a knowledgeResource
     *
     * @param KnowledgeResource $knowledgeResource
     * @param int               $authorId
     *
     * @throws NoAuthorException
     * @return Knowledge
     */
    public function createFromResource(
        KnowledgeResource $knowledgeResource,
        $authorId = null
    );

    /**
     * Create and add an knowledge from a KnowledgeResource
     *
     * @param KnowledgeResource $knowledgeResource
     * @param int               $authorId
     *
     * @return Knowledge
     */
    public function createAndAdd(KnowledgeResource $knowledgeResource, $authorId);

    /**
     * Create or update a Knowledge object from a KnowledgeResource
     *
     * @param KnowledgeResource $knowledgeResource
     * @param Knowledge         $knowledge
     *
     * @throws NoAuthorException
     * @return Knowledge
     */
    public function updateFromResource(
        KnowledgeResource $knowledgeResource,
        $knowledge
    );

    /**
     * Save a knowledge given in form of a KnowledgeResource
     *
     * @param KnowledgeResource $knowledgeResource
     * @param int               $resourceId
     *
     * @return Knowledge
     */
    public function edit(
        KnowledgeResource $knowledgeResource,
        $resourceId
    );

    /**
     * Save a knowledge
     *
     * @param Knowledge $knowledge
     *
     * @return Knowledge
     */
    public function save(Knowledge $knowledge);

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
     * Delete a knowledge
     *
     * @param $knowledgeId
     *
     * @Transactional
     */
    public function remove($knowledgeId);

    /**
     * Edit the required knowledges
     *
     * @param int             $knowledgeId
     * @param ArrayCollection $requiredKnowledges
     *
     * @return Knowledge
     */
    public function editRequiredKnowledges($knowledgeId, ArrayCollection $requiredKnowledges);

    /**
     * Get a knowledge by id
     *
     * @param $knowledgeId
     *
     * @return Knowledge
     */
    public function get($knowledgeId);

    /**
     * Get a list of knowledges
     *
     * @param CollectionInformation $collectionInformation The collection information
     * @param int                   $authorId
     *
     * @return PaginatorInterface
     */
    public function getAll(
        CollectionInformation $collectionInformation = null,
        $authorId = null
    );
}
