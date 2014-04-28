<?php
namespace SimpleIT\ClaireExerciseBundle\Controller\Api\ExerciseModel;

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
 * API OwnerExerciseModel Controller
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class OwnerExerciseModelController extends ApiController
{
    /**
     * Get a specific OwnerExerciseModel resource
     *
     * @param int $ownerExerciseModelId
     *
     * @throws ApiNotFoundException
     * @return ApiGotResponse
     */
    public function viewAction($ownerExerciseModelId)
    {
        try {
            $oem = $this->get('simple_it.exercise.owner_exercise_model')->get(
                $ownerExerciseModelId
            );
            $oemResource = OwnerExerciseModelResourceFactory::create($oem);

            return new ApiGotResponse($oemResource, array("oem", 'Default'));

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(OwnerExerciseModelResource::RESOURCE_NAME);
        }
    }

    /**
     * Get the list of exercise models
     *
     * @param CollectionInformation $collectionInformation
     *
     * @throws ApiBadRequestException
     * @return ApiPaginatedResponse
     */
    public function listAction(CollectionInformation $collectionInformation)
    {
        try {

            $oems = $this->get('simple_it.exercise.owner_exercise_model')->getAll(
                $collectionInformation
            );

            $oemResources = OwnerExerciseModelResourceFactory::createCollection($oems);

            return new ApiPaginatedResponse($oemResources, $oems, array(
                'owner_exercise_model_list',
                'Default'
            ));
        } catch (FilterException $fe) {
            throw new ApiBadRequestException($fe->getMessage());
        }
    }

    /**
     * Delete an owner exercise Model
     *
     * @param int $ownerExerciseModelId
     *
     * @throws ApiNotFoundException
     * @return ApiDeletedResponse
     */
    public function deleteAction($ownerExerciseModelId)
    {
        try {
            $this->get('simple_it.exercise.owner_exercise_model')->remove($ownerExerciseModelId);

            return new ApiDeletedResponse();

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(ExerciseModelResource::RESOURCE_NAME);
        }
    }

    /**
     * Edit an owner resource
     *
     * @param OwnerExerciseModelResource $ownerExerciseModelResource
     * @param int                        $ownerExerciseModelId
     *
     * @throws ApiBadRequestException
     * @throws ApiNotFoundException
     * @throws ApiConflictException
     * @return ApiEditedResponse
     */
    public function editAction(
        OwnerExerciseModelResource $ownerExerciseModelResource,
        $ownerExerciseModelId
    )
    {
        try {
            $this->validateResource($ownerExerciseModelResource, array('edit'));

            $ownerExerciseModel = $this->get('simple_it.exercise.owner_exercise_model')->edit
                (
                    $ownerExerciseModelResource,
                    $ownerExerciseModelId
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
