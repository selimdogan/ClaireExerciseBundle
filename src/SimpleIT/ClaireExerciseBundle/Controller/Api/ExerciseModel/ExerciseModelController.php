<?php
namespace SimpleIT\ClaireExerciseBundle\Controller\Api\ExerciseModel;

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
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\ExerciseModel;
use SimpleIT\ClaireExerciseBundle\Exception\EntityDeletionException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModelResource;
use SimpleIT\CoreBundle\Exception\ExistingObjectException;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Exception\FilterException;
use SimpleIT\ClaireExerciseBundle\Exception\InvalidTypeException;
use SimpleIT\ClaireExerciseBundle\Exception\NoAuthorException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModelResourceFactory;
use SimpleIT\Utils\Collection\CollectionInformation;
use Symfony\Component\HttpFoundation\Request;

/**
 * API Exercise Model controller
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ExerciseModelController extends ApiController
{
    /**
     * Get a specific exerciseModel resource
     *
     * @param int $exerciseModelId Exercise Model id
     *
     * @throws ApiNotFoundException
     * @return ApiGotResponse
     */
    public function viewAction($exerciseModelId)
    {
        try {
            /** @var ExerciseModel $exerciseModel */
            $exerciseModelResource = $this->get(
                'simple_it.exercise.exercise_model'
            )->getContentFullResource
                (
                    $exerciseModelId
                );

            return new ApiGotResponse($exerciseModelResource, array("details", 'Default'));

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(ExerciseModelResource::RESOURCE_NAME);
        }
    }

    /**
     * Get the list of exercise models. In the collection information filters (url filters),
     * type is used for the type of the exercise and all other values are used to search in
     * metadata.
     *
     * @param CollectionInformation $collectionInformation
     *
     * @throws ApiBadRequestException
     * @return ApiPaginatedResponse
     */
    public function listAction(CollectionInformation $collectionInformation)
    {
        try {
            $exerciseModels = $this->get('simple_it.exercise.exercise_model')->getAll(
                $collectionInformation
            );

            $exerciseModelResources = $this->get(
                'simple_it.exercise.exercise_model'
            )->getAllContentFullResourcesFromEntityList(
                $exerciseModels
            );

            return new ApiPaginatedResponse($exerciseModelResources, $exerciseModels, array(
                'list',
                'Default'
            ));
        } catch (FilterException $fe) {
            throw new ApiBadRequestException($fe->getMessage());
        }
    }

    /**
     * Create a new model (without metadata)
     *
     * @param ExerciseModelResource $modelResource
     *
     * @throws ApiBadRequestException
     * @throws ApiNotFoundException
     * @return ApiResponse
     */
    public function createAction(
        ExerciseModelResource $modelResource
    )
    {
        try {
            $userId = null;
            if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
                $userId = $this->get('security.context')->getToken()->getUser()->getId();
            }

            $this->validateResource($modelResource, array('create', 'Default'));

            $modelResource->setAuthor($userId);
            $modelResource->setOwner($userId);

            $model = $this->get('simple_it.exercise.exercise_model')->createAndAdd
                (
                    $modelResource
                );

            $modelResource = ExerciseModelResourceFactory::create($model);

            return new ApiCreatedResponse($modelResource, array("details", 'Default'));

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(ExerciseModelResource::RESOURCE_NAME);
        } catch (NoAuthorException $nae) {
            throw new ApiBadRequestException($nae->getMessage());
        }
    }

    /**
     * Edit a model
     *
     * @param ExerciseModelResource $modelResource
     * @param int                   $exerciseModelId
     *
     * @throws ApiBadRequestException
     * @throws ApiNotFoundException
     * @throws ApiConflictException
     * @return ApiEditedResponse
     */
    public function editAction(ExerciseModelResource $modelResource, $exerciseModelId)
    {
        try {
            $this->validateResource($modelResource, array('edit', 'Default'));

            $modelResource->setId($exerciseModelId);

            $resource = $this->get('simple_it.exercise.exercise_model')->edit
                (
                    $modelResource
                );
            $modelResource = ExerciseModelResourceFactory::create($resource);

            return new ApiEditedResponse($modelResource);

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(ExerciseModelResource::RESOURCE_NAME);
        } catch (ExistingObjectException $eoe) {
            throw new ApiConflictException($eoe->getMessage());
        } catch (NoAuthorException $nae) {
            throw new ApiBadRequestException($nae->getMessage());
        } catch (InvalidTypeException $ite) {
            throw new ApiBadRequestException($ite->getMessage());
        }
    }

    /**
     * Delete a model
     *
     * @param int $exerciseModelId
     *
     * @throws \SimpleIT\ApiBundle\Exception\ApiNotFoundException
     * @throws \SimpleIT\ApiBundle\Exception\ApiBadRequestException
     * @return ApiDeletedResponse
     */
    public function deleteAction($exerciseModelId)
    {
        try {
            $this->get('simple_it.exercise.exercise_model')->remove($exerciseModelId);

            return new ApiDeletedResponse();

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(ExerciseModelResource::RESOURCE_NAME);
        } catch (EntityDeletionException $ede) {
            throw new ApiBadRequestException($ede->getMessage());
        }
    }
}
