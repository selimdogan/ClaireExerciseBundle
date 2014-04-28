<?php

namespace SimpleIT\ClaireExerciseBundle\Controller\Api\ExerciseResource;

use Doctrine\Common\Collections\ArrayCollection;
use SimpleIT\ApiBundle\Controller\ApiController;
use SimpleIT\ApiBundle\Exception\ApiBadRequestException;
use SimpleIT\ApiBundle\Exception\ApiConflictException;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ApiBundle\Model\ApiCreatedResponse;
use SimpleIT\ApiBundle\Model\ApiDeletedResponse;
use SimpleIT\ApiBundle\Model\ApiEditedResponse;
use SimpleIT\ApiBundle\Model\ApiResponse;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ResourceResource;
use SimpleIT\CoreBundle\Exception\ExistingObjectException;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Exception\EntityAlreadyExistsException;
use SimpleIT\ClaireExerciseBundle\Exception\EntityDeletionException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\RequiredResourceResourceFactory;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ResourceResourceFactory;

/**
 * Class RequiredResourceByResourceController
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class RequiredResourceByResourceController extends ApiController
{
    /**
     * Add a required resource to the resource
     *
     * @param $resourceId
     * @param $reqResId
     *
     * @throws ApiBadRequestException
     * @throws ApiNotFoundException
     * @return ApiResponse
     */
    public function addAction($resourceId, $reqResId)
    {
        try {
            $exerciseResource = $this->get('simple_it.exercise.exercise_resource')
                ->addRequiredResource
                (
                    $resourceId,
                    $reqResId
                );

            $resourceResource = ResourceResourceFactory::create($exerciseResource);

            return new ApiCreatedResponse($resourceResource, array("details", 'Default'));

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(ResourceResource::RESOURCE_NAME);
        } catch (EntityAlreadyExistsException $eaee) {
            throw new ApiBadRequestException($eaee->getMessage());
        }
    }

    /**
     * Delete a required resource from the resource
     *
     * @param $resourceId
     * @param $reqResId
     *
     * @throws ApiBadRequestException
     * @throws ApiNotFoundException
     * @return ApiResponse
     */
    public function deleteAction($resourceId, $reqResId)
    {
        try {
            $this->get('simple_it.exercise.exercise_resource')
                ->deleteRequiredResource
                (
                    $resourceId,
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
     * Edit the required resources of a resource
     *
     * @param ArrayCollection $requiredResources
     * @param int             $resourceId
     *
     * @throws ApiNotFoundException
     * @throws ApiConflictException
     * @return ApiEditedResponse
     */
    public function editAction(ArrayCollection $requiredResources, $resourceId)
    {
        try {
            $resources = $this->get('simple_it.exercise.exercise_resource')
                ->editRequiredResource
                (
                    $resourceId,
                    $requiredResources
                );

            $resourceResource = RequiredResourceResourceFactory::createCollection($resources);

            return new ApiEditedResponse($resourceResource);

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(ResourceResource::RESOURCE_NAME);
        } catch (ExistingObjectException $eoe) {
            throw new ApiConflictException($eoe->getMessage());
        }
    }
}
