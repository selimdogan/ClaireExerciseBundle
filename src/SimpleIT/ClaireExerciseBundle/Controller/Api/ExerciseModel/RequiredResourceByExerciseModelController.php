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
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseModelResource;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ResourceResource;
use SimpleIT\CoreBundle\Exception\ExistingObjectException;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Exception\EntityAlreadyExistsException;
use SimpleIT\ClaireExerciseBundle\Exception\EntityDeletionException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModelResourceFactory;
use SimpleIT\ClaireExerciseBundle\Model\Resources\RequiredResourceResourceFactory;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ResourceResourceFactory;

/**
 * Class RequiredResourceByExerciseModelController
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class RequiredResourceByExerciseModelController extends ApiController
{
    /**
     * Add a required resource to the exercise model
     *
     * @param $exerciseModelId
     * @param $reqResId
     *
     * @throws ApiBadRequestException
     * @throws ApiNotFoundException
     * @return ApiResponse
     */
    public function addAction($exerciseModelId, $reqResId)
    {
        try {
            $exerciseResource = $this->get('simple_it.exercise.exercise_model')
                ->addRequiredResource
                (
                    $exerciseModelId,
                    $reqResId
                );

            $exerciseModelResource = ExerciseModelResourceFactory::create($exerciseResource);

            return new ApiCreatedResponse($exerciseModelResource, array("details", 'Default'));

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(ExerciseModelResource::RESOURCE_NAME);
        } catch (EntityAlreadyExistsException $eaee) {
            throw new ApiBadRequestException($eaee->getMessage());
        }
    }

    /**
     * Delete a required resource from the exercise model
     *
     * @param $exerciseModelId
     * @param $reqResId
     *
     * @throws ApiBadRequestException
     * @throws ApiNotFoundException
     * @return ApiResponse
     */
    public function deleteAction($exerciseModelId, $reqResId)
    {
        try {
            $this->get('simple_it.exercise.exercise_model')
                ->deleteRequiredResource
                (
                    $exerciseModelId,
                    $reqResId
                );

            return new ApiDeletedResponse();

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(ResourceResource::RESOURCE_NAME);
        } catch (EntityDeletionException $ede) {
            throw new ApiBadRequestException($ede->getMessage());
        }
    }

    /**
     * Edit the required resources of an exercise model
     *
     * @param ArrayCollection $requiredResources
     * @param int             $exerciseModelId
     *
     * @throws ApiNotFoundException
     * @throws ApiConflictException
     * @return ApiEditedResponse
     */
    public function editAction(ArrayCollection $requiredResources, $exerciseModelId)
    {
        try {
            $resources = $this->get('simple_it.exercise.exercise_model')
                ->editRequiredResource
                (
                    $exerciseModelId,
                    $requiredResources
                );

            $resourceResource = RequiredResourceResourceFactory::createCollection($resources);

            return new ApiEditedResponse($resourceResource);

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(ExerciseModelResource::RESOURCE_NAME);
        } catch (ExistingObjectException $eoe) {
            throw new ApiConflictException($eoe->getMessage());
        }
    }
}
