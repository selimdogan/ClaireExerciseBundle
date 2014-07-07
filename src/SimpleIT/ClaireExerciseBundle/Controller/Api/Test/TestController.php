<?php
namespace SimpleIT\ClaireExerciseBundle\Controller\Api\Test;

use SimpleIT\ClaireExerciseBundle\Controller\BaseController;
use SimpleIT\ClaireExerciseBundle\Exception\Api\ApiNotFoundException;
use SimpleIT\ClaireExerciseBundle\Model\Api\ApiGotResponse;
use SimpleIT\ClaireExerciseBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\TestResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\TestResourceFactory;
use SimpleIT\ClaireExerciseBundle\Model\Collection\CollectionInformation;

/**
 * API Test controller
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class TestController extends BaseController
{
    /**
     * View a test
     *
     * @param int $testId Exercise id
     *
     * @return ApiGotResponse
     * @throws ApiNotFoundException
     */
    public function viewAction($testId)
    {
        try {
            $test = $this->get('simple_it.exercise.test')->get($testId);
            $testResource = TestResourceFactory::create($test);

            return new ApiGotResponse($testResource, array("test", 'Default'));

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(TestResource::RESOURCE_NAME);
        }
    }

    /**
     * Get all the tests
     *
     * @param CollectionInformation $collectionInformation
     *
     * @throws ApiNotFoundException
     * @return ApiGotResponse
     */
    public function listAction(CollectionInformation $collectionInformation)
    {
        try {
            $tests = $this->get('simple_it.exercise.test')->getAll(
                $collectionInformation
            );

            $testResources = TestResourceFactory::createCollection($tests);

            return new ApiGotResponse($testResources, array('list', 'Default'));
        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(TestResource::RESOURCE_NAME);
        }
    }
}
