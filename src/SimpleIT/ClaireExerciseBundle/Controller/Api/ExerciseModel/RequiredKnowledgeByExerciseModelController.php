<?php

namespace SimpleIT\ClaireExerciseBundle\Controller\Api\ExerciseModel;

use Doctrine\Common\Collections\ArrayCollection;
use SimpleIT\ApiBundle\Controller\ApiController;
use SimpleIT\ApiBundle\Exception\ApiBadRequestException;
use SimpleIT\ApiBundle\Exception\ApiConflictException;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ApiBundle\Model\ApiCreatedResponse;
use SimpleIT\ApiBundle\Model\ApiDeletedResponse;
use SimpleIT\ApiBundle\Model\ApiEditedResponse;
use SimpleIT\ApiBundle\Model\ApiResponse;
use SimpleIT\ClaireExerciseBundle\Exception\EntityAlreadyExistsException;
use SimpleIT\ClaireExerciseBundle\Exception\EntityDeletionException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModelResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModelResourceFactory;
use SimpleIT\ClaireExerciseBundle\Model\Resources\KnowledgeResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\RequiredKnowledgeResourceFactory;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ResourceResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ResourceResourceFactory;
use SimpleIT\CoreBundle\Exception\ExistingObjectException;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;

/**
 * Class RequiredKnowledgeByExerciseModelController
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class RequiredKnowledgeByExerciseModelController extends ApiController
{
    /**
     * Add a required knowledge to the exercise model
     *
     * @param $exerciseModelId
     * @param $reqKnoId
     *
     * @throws ApiBadRequestException
     * @throws ApiNotFoundException
     * @return ApiResponse
     */
    public function addAction($exerciseModelId, $reqKnoId)
    {
        try {
            $exerciseModel = $this->get('simple_it.exercise.exercise_model')
                ->addRequiredKnowledge
                (
                    $exerciseModelId,
                    $reqKnoId
                );

            $exerciseModelResource = ExerciseModelResourceFactory::create($exerciseModel);

            return new ApiCreatedResponse($exerciseModelResource, array("details", 'Default'));

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(ExerciseModelResource::RESOURCE_NAME);
        } catch (EntityAlreadyExistsException $eaee) {
            throw new ApiBadRequestException($eaee->getMessage());
        }
    }

    /**
     * Delete a required knowledge from the exercise model
     *
     * @param $exerciseModelId
     * @param $reqKnoId
     *
     * @throws ApiBadRequestException
     * @throws ApiNotFoundException
     * @return ApiResponse
     */
    public function deleteAction($exerciseModelId, $reqKnoId)
    {
        try {
            $this->get('simple_it.exercise.exercise_model')
                ->deleteRequiredKnowledge
                (
                    $exerciseModelId,
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
     * Edit the required knowledges of an exercise model
     *
     * @param ArrayCollection $requiredKnowledges
     * @param int             $exerciseModelId
     *
     * @throws ApiNotFoundException
     * @throws ApiConflictException
     * @return ApiEditedResponse
     */
    public function editAction(ArrayCollection $requiredKnowledges, $exerciseModelId)
    {
        try {
            $knowledges = $this->get('simple_it.exercise.exercise_model')
                ->editRequiredKnowledges
                (
                    $exerciseModelId,
                    $requiredKnowledges
                );

            $knowledgeResource = RequiredKnowledgeResourceFactory::createCollection($knowledges);

            return new ApiEditedResponse($knowledgeResource);

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(ExerciseModelResource::RESOURCE_NAME);
        } catch (ExistingObjectException $eoe) {
            throw new ApiConflictException($eoe->getMessage());
        }
    }
}
