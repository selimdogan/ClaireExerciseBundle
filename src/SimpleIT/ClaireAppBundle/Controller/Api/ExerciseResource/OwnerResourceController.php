<?php
namespace SimpleIT\ExerciseBundle\Controller\ExerciseResource;

use JMS\Serializer\SerializationContext;
use SimpleIT\ApiBundle\Controller\ApiController;
use SimpleIT\ApiBundle\Exception\ApiBadRequestException;
use SimpleIT\ApiBundle\Exception\ApiConflictException;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ApiBundle\Model\ApiDeletedResponse;
use SimpleIT\ApiBundle\Model\ApiEditedResponse;
use SimpleIT\ApiBundle\Model\ApiGotResponse;
use SimpleIT\ApiBundle\Model\ApiPaginatedResponse;
use SimpleIT\ApiBundle\Model\ApiResponse;
use SimpleIT\ApiResourcesBundle\Exercise\OwnerResourceResource;
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
class OwnerResourceController extends ApiController
{
    /**
     * Get a specific OwnerResource resource
     *
     * @param int $ownerResourceId
     *
     * @throws ApiNotFoundException
     * @return ApiGotResponse
     */
    public function viewAction($ownerResourceId)
    {
        try {
            $ownerResource = $this->get('simple_it.exercise.owner_resource')->get(
                $ownerResourceId
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
     *
     * @throws ApiBadRequestException
     * @return ApiPaginatedResponse
     */
    public function listAction(CollectionInformation $collectionInformation)
    {
        $ownerResources = $this->get('simple_it.exercise.owner_resource')->getAll(
            $collectionInformation
        );

        $oemResources = OwnerResourceResourceFactory::createCollection($ownerResources);

        return new ApiPaginatedResponse($oemResources, $ownerResources, array(
            'owner_resource_list',
            'Default'
        ));
    }

    /**
     * Edit an owner resource
     *
     * @param OwnerResourceResource $ownerResourceResource
     * @param int                   $ownerResourceId
     *
     * @throws ApiBadRequestException
     * @throws ApiNotFoundException
     * @throws ApiConflictException
     * @return ApiEditedResponse
     */
    public function editAction(OwnerResourceResource $ownerResourceResource, $ownerResourceId)
    {
        try {
            $this->validateResource($ownerResourceResource, array('edit'));

            $ownerResource = $this->get('simple_it.exercise.owner_resource')->edit
                (
                    $ownerResourceResource,
                    $ownerResourceId
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

    /**
     * Delete an owner resource
     *
     * @param int $ownerResourceId
     *
     * @throws ApiNotFoundException
     * @return ApiDeletedResponse
     */
    public function deleteAction($ownerResourceId)
    {
        try {
            $this->get('simple_it.exercise.owner_resource')->remove($ownerResourceId);

            return new ApiDeletedResponse();

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(OwnerResourceResource::RESOURCE_NAME);
        }
    }
}
