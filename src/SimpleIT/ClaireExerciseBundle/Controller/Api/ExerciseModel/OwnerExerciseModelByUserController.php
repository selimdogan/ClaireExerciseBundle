<?php
namespace SimpleIT\ClaireExerciseBundle\Controller\Api\ExerciseModel;

use SimpleIT\ApiBundle\Controller\ApiController;
use SimpleIT\ApiBundle\Exception\ApiBadRequestException;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ApiBundle\Model\ApiGotResponse;
use SimpleIT\ApiBundle\Model\ApiPaginatedResponse;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\OwnerExerciseModelResource;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Exception\FilterException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\OwnerExerciseModelResourceFactory;
use SimpleIT\Utils\Collection\CollectionInformation;

/**
 * API OwnerExerciseModelByUser Controller
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class OwnerExerciseModelByUserController extends ApiController
{
    /**
     * Get a specific OwnerExerciseModel resource
     *
     * @param int $ownerExerciseModelId
     * @param int $ownerId
     *
     * @throws ApiNotFoundException
     * @return ApiGotResponse
     */
    public function viewAction($ownerExerciseModelId, $ownerId)
    {
        try {
            $ownerExerciseModel = $this->get(
                'simple_it.exercise.owner_exercise_model'
            )->getByIdAndOwner(
                    $ownerExerciseModelId,
                    $ownerId
                );
            $ownerExerciseModelResource = OwnerExerciseModelResourceFactory::create(
                $ownerExerciseModel
            );

            return new ApiGotResponse($ownerExerciseModelResource, array("oem", 'Default'));

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(OwnerExerciseModelResource::RESOURCE_NAME . ' or ' .
            "User");
        }
    }

    /**
     * Get the list of owner resources
     *
     * @param CollectionInformation $collectionInformation
     * @param int                   $ownerId
     *
     * @throws ApiBadRequestException
     * @throws ApiNotFoundException
     * @return ApiPaginatedResponse
     */
    public function listAction(
        CollectionInformation $collectionInformation,
        $ownerId
    )
    {
        try {
            $ownerExerciseModels = $this->get('simple_it.exercise.owner_exercise_model')->getAll(
                $collectionInformation,
                $ownerId
            );

            $oemResources = OwnerExerciseModelResourceFactory::createCollection(
                $ownerExerciseModels
            );

            return new ApiPaginatedResponse($oemResources, $ownerExerciseModels, array(
                'owner_exercise_model_list',
                'Default'
            ));
        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(OwnerExerciseModelResource::RESOURCE_NAME . ' or ' .
            "User");
        } catch (FilterException $fe) {
            throw new ApiBadRequestException($fe->getMessage());
        }
    }
}
