<?php
namespace SimpleIT\ExerciseBundle\Controller\ExerciseResource;

use SimpleIT\ApiBundle\Controller\ApiController;
use SimpleIT\ApiBundle\Exception\ApiBadRequestException;
use SimpleIT\ApiBundle\Exception\ApiConflictException;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ApiBundle\Model\ApiCreatedResponse;
use SimpleIT\ApiBundle\Model\ApiEditedResponse;
use SimpleIT\ApiBundle\Model\ApiGotResponse;
use SimpleIT\ApiBundle\Model\ApiPaginatedResponse;
use SimpleIT\ApiBundle\Model\ApiResponse;
use SimpleIT\ApiResourcesBundle\Exercise\OwnerResourceResource;
use SimpleIT\ApiResourcesBundle\Exercise\ResourceResource;
use SimpleIT\CoreBundle\Exception\ExistingObjectException;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\ExerciseBundle\Exception\NoAuthorException;
use SimpleIT\ExerciseBundle\Model\Resources\OwnerResourceResourceFactory;
use SimpleIT\Utils\Collection\CollectionInformation;
use Symfony\Component\HttpFoundation\Request;

/**
 * API OwnerResource Controller
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class OwnerResourceByResourceController extends ApiController
{
    /**
     * Get a specific OwnerResource resource
     *
     * @param int $ownerResourceId
     * @param int $resourceId
     *
     * @throws ApiNotFoundException
     * @return ApiGotResponse
     */
    public function viewAction($ownerResourceId, $resourceId)
    {
        try {
            $ownerResource = $this->get('simple_it.exercise.owner_resource')->getByIdAndResource(
                $ownerResourceId,
                $resourceId
            );
            $ownerResourceResource = OwnerResourceResourceFactory::create($ownerResource);

            return new ApiGotResponse($ownerResourceResource, array("owner_resource", 'Default'));

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(OwnerResourceResource::RESOURCE_NAME);
        }
    }

    /**
     * Get the list of owner resources
     *
     * @param CollectionInformation $collectionInformation
     * @param int                   $resourceId
     *
     * @throws ApiNotFoundException
     * @return ApiPaginatedResponse
     */
    public function listAction(
        CollectionInformation $collectionInformation,
        $resourceId
    )
    {
        try {
            $ownerResources = $this->get('simple_it.exercise.owner_resource')->getAll(
                $collectionInformation,
                null,
                $resourceId
            );

            $oemResources = OwnerResourceResourceFactory::createCollection($ownerResources);

            return new ApiPaginatedResponse($oemResources, $ownerResources, array(
                'owner_resource_list',
                'Default'
            ));
        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(ResourceResource::RESOURCE_NAME);
        }
    }

    /**
     * Create a new owner resource (without metadata)
     *
     * @param OwnerResourceResource $ownerResourceResource
     * @param int                   $resourceId
     *
     * @throws ApiBadRequestException
     * @throws ApiNotFoundException
     * @return ApiResponse
     */
    public function createAction(OwnerResourceResource $ownerResourceResource, $resourceId)
    {
        try {
            $userId = null;
            if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
                $userId = $this->get('security.context')->getToken()->getUser()->getId();
            } else {
                throw new ApiBadRequestException('Owner must be authenticated to create a resource');
            }

            $this->validateResource($ownerResourceResource, array('create'));

            $ownerResource = $this->get('simple_it.exercise.owner_resource')->createAndAdd
                (
                    $ownerResourceResource,
                    $resourceId,
                    $userId
                );

            $ownerResourceResource = OwnerResourceResourceFactory::create($ownerResource);

            return new ApiCreatedResponse($ownerResourceResource, array("details", 'Default'));

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(OwnerResourceResource::RESOURCE_NAME);
        } catch (NoAuthorException $nae) {
            throw new ApiBadRequestException($nae->getMessage());
        }
    }

    /**
     * Edit an owner resource
     *
     * @param OwnerResourceResource $ownerResourceResource
     * @param int                   $ownerResourceId
     * @param int                   $resourceId
     *
     * @throws ApiBadRequestException
     * @throws ApiNotFoundException
     * @throws ApiConflictException
     * @return ApiEditedResponse
     */
    public function editAction(
        OwnerResourceResource $ownerResourceResource,
        $ownerResourceId,
        $resourceId
    )
    {
        try {
            $this->validateResource($ownerResourceResource, array('edit'));

            $ownerResource = $this->get('simple_it.exercise.owner_resource')->edit
                (
                    $ownerResourceResource,
                    $ownerResourceId,
                    $resourceId
                );
            $ownerResourceResource = OwnerResourceResourceFactory::create($ownerResource);

            return new ApiEditedResponse($ownerResourceResource);

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(OwnerResourceResource::RESOURCE_NAME);
        } catch (ExistingObjectException $eoe) {
            throw new ApiConflictException($eoe->getMessage());
        } catch (NoAuthorException $nae) {
            throw new ApiBadRequestException($nae->getMessage());
        }
    }
}
