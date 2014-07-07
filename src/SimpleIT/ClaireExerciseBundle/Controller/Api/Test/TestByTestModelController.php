<?php

namespace SimpleIT\ClaireExerciseBundle\Controller\Api\Test;

use SimpleIT\ClaireExerciseBundle\Controller\BaseController;
use SimpleIT\ClaireExerciseBundle\Exception\Api\ApiNotFoundException;
use SimpleIT\ClaireExerciseBundle\Model\Api\ApiCreatedResponse;
use SimpleIT\ClaireExerciseBundle\Model\Api\ApiGotResponse;
use SimpleIT\ClaireExerciseBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\TestModelResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\TestResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\TestResourceFactory;
use SimpleIT\ClaireExerciseBundle\Model\Collection\CollectionInformation;

/**
 * Class TestByTestModelController
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class TestByTestModelController extends BaseController
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
