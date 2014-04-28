<?php
namespace SimpleIT\ClaireExerciseBundle\Controller\Api\Test;

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
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\TestModelResource;
use SimpleIT\CoreBundle\Exception\ExistingObjectException;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Exception\NoAuthorException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\TestModelResourceFactory;
use SimpleIT\Utils\Collection\CollectionInformation;
use Symfony\Component\HttpFoundation\Request;

/**
 * API Test Model controller
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class TestModelController extends ApiController
{
    /**
     * Get a specific test Model resource
     *
     * @param int $testModelId Exercise Model id
     *
     * @throws ApiNotFoundException
     * @return ApiGotResponse
     */
    public function viewAction($testModelId)
    {
        try {
            $testModel = $this->get('simple_it.exercise.test_model')->get($testModelId);
            $testModelResource = TestModelResourceFactory::create($testModel);

            return new ApiGotResponse($testModelResource, array("details", 'Default'));

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(TestModelResource::RESOURCE_NAME);
        }
    }

    /**
     * Get the list of test models.
     *
     * @param CollectionInformation $collectionInformation
     *
     * @return ApiPaginatedResponse
     */
    public function listAction(CollectionInformation $collectionInformation)
    {
        $testModels = $this->get('simple_it.exercise.test_model')->getAll(
            $collectionInformation
        );

        $testModelResources = TestModelResourceFactory::createCollection($testModels);

        return new ApiPaginatedResponse($testModelResources, $testModels, array('list', 'Default'));
    }

    /**
     * Create a new test model
     *
     * @param TestModelResource $testModelResource
     *
     * @throws ApiBadRequestException
     * @throws ApiNotFoundException
     * @return ApiResponse
     */
    public function createAction(
        TestModelResource $testModelResource
    )
    {
        try {
            $userId = null;
            if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
                $userId = $this->get('security.context')->getToken()->getUser()->getId();
            }

            $this->validateResource($testModelResource, array('create', 'Default'));

            $model = $this->get('simple_it.exercise.test_model')->createAndAdd
                (
                    $testModelResource,
                    $userId
                );

            $testModelResource = TestModelResourceFactory::create($model);

            return new ApiCreatedResponse($testModelResource, array("details", 'Default'));

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(TestModelResource::RESOURCE_NAME);
        } catch (NoAuthorException $nae) {
            throw new ApiBadRequestException($nae->getMessage());
        }
    }

    /**
     * Edit a model
     *
     * @param TestModelResource $testModelResource
     * @param int               $testModelId
     *
     * @throws ApiBadRequestException
     * @throws ApiNotFoundException
     * @throws ApiConflictException
     * @return ApiEditedResponse
     */
    public function editAction(TestModelResource $testModelResource, $testModelId)
    {
        try {
            $this->validateResource($testModelResource, array('edit', 'Default'));

            $resource = $this->get('simple_it.exercise.test_model')->edit
                (
                    $testModelResource,
                    $testModelId
                );
            $testModelResource = TestModelResourceFactory::create($resource);

            return new ApiEditedResponse($testModelResource);

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(TestModelResource::RESOURCE_NAME);
        } catch (ExistingObjectException $eoe) {
            throw new ApiConflictException($eoe->getMessage());
        } catch (NoAuthorException $nae) {
            throw new ApiBadRequestException($nae->getMessage());
        }
    }

    /**
     * Delete a model
     *
     * @param int $testModelId
     *
     * @throws ApiNotFoundException
     * @return ApiDeletedResponse
     */
    public function deleteAction($testModelId)
    {
        try {
            $this->get('simple_it.exercise.test_model')->remove($testModelId);

            return new ApiDeletedResponse();

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(TestModelResource::RESOURCE_NAME);
        }
    }
}
