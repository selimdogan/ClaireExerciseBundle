<?php

namespace SimpleIT\ClaireExerciseBundle\Controller\Api\Test;

use SimpleIT\ClaireExerciseBundle\Controller\Api\ApiController;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ApiBundle\Model\ApiCreatedResponse;
use SimpleIT\ApiBundle\Model\ApiGotResponse;
use SimpleIT\ClaireExerciseBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\TestModelResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\TestResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\TestResourceFactory;
use SimpleIT\Utils\Collection\CollectionInformation;

/**
 * Class TestByTestModelController
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class TestByTestModelController extends ApiController
{
    /**
     * Generate a test from a test model
     *
     * @param $testModelId
     *
     * @return ApiCreatedResponse
     * @throws ApiNotFoundException
     */
    public function createAction($testModelId)
    {
        try {
            $test = $this->get('simple_it.exercise.test')->add($testModelId);

            $testResource = TestResourceFactory::create($test);

            return new ApiCreatedResponse($testResource, array('test', 'Default'));

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(TestResource::RESOURCE_NAME);
        }
    }

    /**
     * List the tests for a test model
     *
     * @param CollectionInformation $collectionInformation
     * @param int                   $testModelId
     *
     * @throws ApiNotFoundException
     * @return ApiGotResponse
     */
    public function listAction(CollectionInformation $collectionInformation, $testModelId)
    {
        try {
            $tests = $this->get('simple_it.exercise.test')->getAll
                (
                    $collectionInformation,
                    $testModelId
                );

            $testResources = TestResourceFactory::createCollection($tests);

            return new ApiGotResponse($testResources, array('list', 'Default'));
        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(TestModelResource::RESOURCE_NAME);
        }
    }
}
