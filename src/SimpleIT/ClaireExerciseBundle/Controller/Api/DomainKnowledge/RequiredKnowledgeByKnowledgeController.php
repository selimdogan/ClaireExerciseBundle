<?php

namespace SimpleIT\ClaireExerciseBundle\Controller\Api\DomainKnowledge;

use Doctrine\Common\Collections\ArrayCollection;
use SimpleIT\ApiBundle\Controller\ApiController;
use SimpleIT\ApiBundle\Exception\ApiBadRequestException;
use SimpleIT\ApiBundle\Exception\ApiConflictException;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ApiBundle\Model\ApiCreatedResponse;
use SimpleIT\ApiBundle\Model\ApiDeletedResponse;
use SimpleIT\ApiBundle\Model\ApiEditedResponse;
use SimpleIT\ApiBundle\Model\ApiResponse;
use SimpleIT\ClaireExerciseBundle\Model\Resources\KnowledgeResource;
use SimpleIT\CoreBundle\Exception\ExistingObjectException;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Exception\EntityAlreadyExistsException;
use SimpleIT\ClaireExerciseBundle\Exception\EntityDeletionException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\RequiredKnowledgeResourceFactory;
use SimpleIT\ClaireExerciseBundle\Model\Resources\KnowledgeResourceFactory;

/**
 * Class RequiredKnowledgeByKnowledgeController
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class RequiredKnowledgeByKnowledgeController extends ApiController
{
    /**
     * Add a required knowledge to the knowledge
     *
     * @param int $knowledgeId
     * @param int $reqKnoId
     *
     * @throws ApiBadRequestException
     * @throws ApiNotFoundException
     * @return ApiResponse
     */
    public function addAction($knowledgeId, $reqKnoId)
    {
        try {
            $knowledge = $this->get('simple_it.exercise.knowledge')
                ->addRequiredKnowledge
                (
                    $knowledgeId,
                    $reqKnoId
                );

            $knowledgeResource = KnowledgeResourceFactory::create($knowledge);

            return new ApiCreatedResponse($knowledgeResource, array("details", 'Default'));

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(KnowledgeResource::RESOURCE_NAME);
        } catch (EntityAlreadyExistsException $eaee) {
            throw new ApiBadRequestException($eaee->getMessage());
        }
    }

    /**
     * Delete a required knowledge from the knowledge
     *
     * @param int $knowledgeId
     * @param int $reqKnoId
     *
     * @throws ApiBadRequestException
     * @throws ApiNotFoundException
     * @return ApiResponse
     */
    public function deleteAction($knowledgeId, $reqKnoId)
    {
        try {
            $this->get('simple_it.exercise.knowledge')
                ->deleteRequiredKnowledge
                (
                    $knowledgeId,
                    $reqKnoId
                );

            return new ApiDeletedResponse();

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(KnowledgeResource::RESOURCE_NAME);
        } catch (EntityDeletionException $ede) {
            throw new ApiBadRequestException($ede->getMessage());
        }
    }

    /**
     * Edit the required resources of a resource
     *
     * @param ArrayCollection $requiredKnowledges
     * @param int             $knowledgeId
     *
     * @throws ApiNotFoundException
     * @throws ApiConflictException
     * @return ApiEditedResponse
     */
    public function editAction(ArrayCollection $requiredKnowledges, $knowledgeId)
    {
        try {
            $knowledges = $this->get('simple_it.exercise.knowledge')
                ->editRequiredKnowledges
                (
                    $knowledgeId,
                    $requiredKnowledges
                );

            $knowledgeResource = RequiredKnowledgeResourceFactory::createCollection($knowledges);

            return new ApiEditedResponse($knowledgeResource);

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(KnowledgeResource::RESOURCE_NAME);
        } catch (ExistingObjectException $eoe) {
            throw new ApiConflictException($eoe->getMessage());
        }
    }
}
