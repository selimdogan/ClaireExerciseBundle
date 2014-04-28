<?php
namespace SimpleIT\ClaireExerciseBundle\Controller\Api\ExerciseModel;

use SimpleIT\ApiBundle\Controller\ApiController;
use SimpleIT\ApiBundle\Exception\ApiBadRequestException;
use SimpleIT\ApiBundle\Exception\ApiConflictException;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ApiBundle\Model\ApiCreatedResponse;
use SimpleIT\ApiBundle\Model\ApiEditedResponse;
use SimpleIT\ApiBundle\Model\ApiGotResponse;
use SimpleIT\ApiBundle\Model\ApiPaginatedResponse;
use SimpleIT\ApiBundle\Model\ApiResponse;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseModelResource;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\OwnerExerciseModelResource;
use SimpleIT\CoreBundle\Exception\ExistingObjectException;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Exception\FilterException;
use SimpleIT\ClaireExerciseBundle\Exception\NoAuthorException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\OwnerExerciseModelResourceFactory;
use SimpleIT\Utils\Collection\CollectionInformation;
use Symfony\Component\HttpFoundation\Request;

/**
 * API OwnerExerciseModelByExerciseModel Controller
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class OwnerExerciseModelByExerciseModelController extends ApiController
{
    /**
     * Get a specific OwnerExerciseModel
     *
     * @param int $ownerExerciseModelId
     * @param int $exerciseModelId
     *
     * @throws ApiNotFoundException
     * @return ApiGotResponse
     */
    public function viewAction($ownerExerciseModelId, $exerciseModelId)
    {
        try {
            $exerciseModel = $this->get('simple_it.exercise.owner_exercise_model')
                ->getByIdAndModel(
                    $ownerExerciseModelId,
                    $exerciseModelId
                );
            $ownerModelResource = OwnerExerciseModelResourceFactory::create($exerciseModel);

            return new ApiGotResponse($ownerModelResource, array("oem", 'Default'));

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(OwnerExerciseModelResource::RESOURCE_NAME);
        }
    }

    /**
     * Get the list of owner exercise models
     *
     * @param CollectionInformation $collectionInformation
     * @param int                   $exerciseModelId
     *
     * @throws ApiBadRequestException
     * @throws ApiNotFoundException
     * @return ApiPaginatedResponse
     */
    public function listAction(
        CollectionInformation $collectionInformation,
        $exerciseModelId
    )
    {
        try {
            $exerciseModels = $this->get('simple_it.exercise.owner_exercise_model')->getAll(
                $collectionInformation,
                null,
                $exerciseModelId
            );

            $oemResources = OwnerExerciseModelResourceFactory::createCollection($exerciseModels);

            return new ApiPaginatedResponse(
                $oemResources,
                $exerciseModels,
                array('owner_exercise_model_list', 'Default')
            );
        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(ExerciseModelResource::RESOURCE_NAME);
        } catch (FilterException $fe) {
            throw new ApiBadRequestException($fe->getMessage());
        }
    }

    /**
     * Create a new owner exercise model (without metadata)
     *
     * @param OwnerExerciseModelResource $ownerExerciseModelResource
     * @param int                        $exerciseModelId
     *
     * @throws ApiBadRequestException
     * @throws ApiNotFoundException
     * @return ApiResponse
     */
    public function createAction(
        OwnerExerciseModelResource $ownerExerciseModelResource,
        $exerciseModelId
    )
    {
        try {
            $userId = null;
            if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
                $userId = $this->get('security.context')->getToken()->getUser()->getId();
            } else {
                throw new ApiBadRequestException('Owner must be authenticated to create a resource');
            }

            $this->validateResource($ownerExerciseModelResource, array('create'));

            $ownerExerciseModel = $this->get(
                'simple_it.exercise.owner_exercise_model'
            )->createAndAdd
                (
                    $ownerExerciseModelResource,
                    $exerciseModelId,
                    $userId
                );

            $ownerExerciseModelResource = OwnerExerciseModelResourceFactory::create(
                $ownerExerciseModel
            );

            return new ApiCreatedResponse($ownerExerciseModelResource, array("details", 'Default'));

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(OwnerExerciseModelResource::RESOURCE_NAME);
        } catch (NoAuthorException $nae) {
            throw new ApiBadRequestException($nae->getMessage());
        }
    }

    /**
     * Edit an owner resource
     *
     * @param OwnerExerciseModelResource $ownerExerciseModelResource
     * @param int                        $ownerExerciseModelId
     * @param int                        $exerciseModelId
     *
     * @throws ApiBadRequestException
     * @throws ApiNotFoundException
     * @throws ApiConflictException
     * @return ApiEditedResponse
     */
    public function editAction(
        OwnerExerciseModelResource $ownerExerciseModelResource,
        $ownerExerciseModelId,
        $exerciseModelId
    )
    {
        try {
            $this->validateResource($ownerExerciseModelResource, array('edit'));

            $ownerExerciseModel = $this->get('simple_it.exercise.owner_exercise_model')->edit
                (
                    $ownerExerciseModelResource,
                    $ownerExerciseModelId,
                    $exerciseModelId
                );
            $ownerExerciseModelResource = OwnerExerciseModelResourceFactory::create(
                $ownerExerciseModel
            );

            return new ApiEditedResponse($ownerExerciseModelResource);

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(OwnerExerciseModelResource::RESOURCE_NAME);
        } catch (ExistingObjectException $eoe) {
            throw new ApiConflictException($eoe->getMessage());
        } catch (NoAuthorException $nae) {
            throw new ApiBadRequestException($nae->getMessage());
        }
    }
}
