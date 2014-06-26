<?php
namespace SimpleIT\ClaireExerciseBundle\Controller\Api\ExerciseResource;

use Doctrine\DBAL\DBALException;
use SimpleIT\ClaireExerciseBundle\Exception\Api\ApiBadRequestException;
use SimpleIT\ClaireExerciseBundle\Exception\Api\ApiConflictException;
use SimpleIT\ClaireExerciseBundle\Exception\Api\ApiNotFoundException;
use SimpleIT\ClaireExerciseBundle\Model\Api\ApiCreatedResponse;
use SimpleIT\ClaireExerciseBundle\Model\Api\ApiDeletedResponse;
use SimpleIT\ClaireExerciseBundle\Model\Api\ApiEditedResponse;
use SimpleIT\ClaireExerciseBundle\Model\Api\ApiGotResponse;
use SimpleIT\ClaireExerciseBundle\Model\Api\ApiResponse;
use SimpleIT\ClaireExerciseBundle\Controller\Api\ApiController;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseResource\ExerciseResource;
use SimpleIT\ClaireExerciseBundle\Exception\EntityDeletionException;
use SimpleIT\ClaireExerciseBundle\Exception\NoAuthorException;
use SimpleIT\ClaireExerciseBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ResourceResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ResourceResourceFactory;
use SimpleIT\ClaireExerciseBundle\Model\Collection\CollectionInformation;
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
            $resourceResource = $this->get(
                'simple_it.exercise.exercise_resource'
            )->getContentFullResource($resourceId, $this->getUserId());

            return new ApiGotResponse($resourceResource, array("details", 'Default'));

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(ResourceResource::RESOURCE_NAME);
        }
    }

    /**
     * Get all items
     *
     * @param CollectionInformation $collectionInformation
     *
     * @throws \SimpleIT\ClaireExerciseBundle\Exception\Api\ApiNotFoundException
     * @return ApiGotResponse
     */
    public function listAction(CollectionInformation $collectionInformation)
    {
        try {
            $resources = $this->get('simple_it.exercise.exercise_resource')->getAll(
                $collectionInformation,
                $this->getUserId()
            );

            $resourceResources = ResourceResourceFactory::createCollection($resources);

            return new ApiGotResponse($resourceResources, array(
                'details',
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
            $userId = $this->getUserId();

            $this->validateResource($resourceResource, array('create', 'Default'));

            $resourceResource->setAuthor($userId);
            $resourceResource->setOwner($userId);

            /** @var ExerciseResource $exerciseResource */
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

            $resourceResource->setId($resourceId);
            $resource = $this->get('simple_it.exercise.exercise_resource')->edit
                (
                    $resourceResource,
                    $this->getUserId()
                );
            $resourceResource = ResourceResourceFactory::create($resource);

            return new ApiEditedResponse($resourceResource);

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(ResourceResource::RESOURCE_NAME);
        } catch (DBALException $eoe) {
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
     * @throws \SimpleIT\ClaireExerciseBundle\Exception\Api\ApiBadRequestException
     * @throws \SimpleIT\ClaireExerciseBundle\Exception\Api\ApiNotFoundException
     * @return ApiDeletedResponse
     */
    public function deleteAction($resourceId)
    {
        try {
            $this->get('simple_it.exercise.exercise_resource')->remove(
                $resourceId,
                $this->getUserId()
            );

            return new ApiDeletedResponse();

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(ResourceResource::RESOURCE_NAME);
        } catch (EntityDeletionException $ede) {
            throw new ApiBadRequestException($ede->getMessage());
        }
    }
}
