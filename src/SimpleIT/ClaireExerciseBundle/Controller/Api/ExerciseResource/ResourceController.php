<?php
namespace SimpleIT\ClaireExerciseBundle\Controller\Api\ExerciseResource;

use SimpleIT\ApiBundle\Controller\ApiController;
use SimpleIT\ApiBundle\Exception\ApiBadRequestException;
use SimpleIT\ApiBundle\Exception\ApiConflictException;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ApiBundle\Model\ApiCreatedResponse;
use SimpleIT\ApiBundle\Model\ApiDeletedResponse;
use SimpleIT\ApiBundle\Model\ApiEditedResponse;
use SimpleIT\ApiBundle\Model\ApiGotResponse;
use SimpleIT\ApiBundle\Model\ApiPaginatedResponse;
use SimpleIT\ApiBundle\Model\ApiResponse;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ResourceResource;
use SimpleIT\CoreBundle\Exception\ExistingObjectException;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Exception\NoAuthorException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ResourceResourceFactory;
use Symfony\Component\HttpFoundation\Request;

/**
 * API Resource controller
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ResourceController extends ApiController
{
    /**
     * View action. View a resource.
     *
     * @param int $resourceId
     *
     * @throws ApiNotFoundException
     * @return ApiGotResponse
     */
    public function viewAction($resourceId)
    {
        try {
            // Call to the resource service to get the resource
            $resource = $this->get('simple_it.exercise.exercise_resource')->get($resourceId);

            $resourceResource = ResourceResourceFactory::create($resource, false);

            return new ApiGotResponse($resourceResource, array("details", 'Default'));

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(ResourceResource::RESOURCE_NAME);
        }
    }

    /**
     * Get all items
     *
     * @throws ApiNotFoundException
     * @return ApiPaginatedResponse
     */
    public function listAction()
    {
        try {
            $resources = $this->get('simple_it.exercise.exercise_resource')->getAll();

            $resourceResources = ResourceResourceFactory::createCollection($resources);

            return new ApiPaginatedResponse($resourceResources, $resources, array(
                'list',
                'Default'
            ));
        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(ResourceResource::RESOURCE_NAME);
        }
    }

    /**
     * Create a new resource (without metadata)
     *
     * @param ResourceResource $resourceResource
     *
     * @throws ApiBadRequestException
     * @throws ApiNotFoundException
     * @return ApiResponse
     */
    public function createAction(
        ResourceResource $resourceResource
    )
    {
        try {
            $userId = null;
            if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
                $userId = $this->get('security.context')->getToken()->getUser()->getId();
            }

            $this->validateResource($resourceResource, array('create', 'Default'));

            $resourceResource->setAuthor($userId);
            $resourceResource->setOwner($userId);

            $exerciseResource = $this->get('simple_it.exercise.exercise_resource')->createAndAdd
                (
                    $resourceResource,
                    $userId
                );

            $resourceResource = ResourceResourceFactory::create($exerciseResource);

            return new ApiCreatedResponse($resourceResource, array("details", 'Default'));

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(ResourceResource::RESOURCE_NAME);
        } catch (NoAuthorException $nae) {
            throw new ApiBadRequestException($nae->getMessage());
        }
    }

    /**
     * Edit a resource
     *
     * @param ResourceResource $resourceResource   resource resource
     * @param int              $resourceId         Category if
     *
     * @throws ApiBadRequestException
     * @throws ApiNotFoundException
     * @throws ApiConflictException
     * @return ApiEditedResponse
     */
    public function editAction(ResourceResource $resourceResource, $resourceId)
    {
        try {
            $this->validateResource($resourceResource, array('edit', 'Default'));

            $resource = $this->get('simple_it.exercise.exercise_resource')->edit
                (
                    $resourceResource,
                    $resourceId
                );
            $resourceResource = ResourceResourceFactory::create($resource);

            return new ApiEditedResponse($resourceResource);

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(ResourceResource::RESOURCE_NAME);
        } catch (ExistingObjectException $eoe) {
            throw new ApiConflictException($eoe->getMessage());
        } catch (NoAuthorException $nae) {
            throw new ApiBadRequestException($nae->getMessage());
        }
    }

    /**
     * Delete a resource
     *
     * @param int $resourceId
     *
     * @throws ApiNotFoundException
     * @return ApiDeletedResponse
     */
    public function deleteAction($resourceId)
    {
        try {
            $this->get('simple_it.exercise.exercise_resource')->remove($resourceId);

            return new ApiDeletedResponse();

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(ResourceResource::RESOURCE_NAME);
        }
    }
}
