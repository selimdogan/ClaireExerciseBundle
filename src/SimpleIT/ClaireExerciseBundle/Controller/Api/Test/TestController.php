<?php
namespace SimpleIT\ClaireExerciseBundle\Controller\Api\Test;

use SimpleIT\ApiBundle\Controller\ApiController;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ApiBundle\Model\ApiGotResponse;
use SimpleIT\ApiBundle\Model\ApiPaginatedResponse;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\TestResource;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\TestResourceFactory;
use SimpleIT\Utils\Collection\CollectionInformation;

/**
 * API Test controller
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class TestController extends ApiController
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
     * @return ApiPaginatedResponse
     */
    public function listAction(CollectionInformation $collectionInformation)
    {
        try {
            $tests = $this->get('simple_it.exercise.test')->getAll(
                $collectionInformation
            );

            $testResources = TestResourceFactory::createCollection($tests);

            return new ApiPaginatedResponse($testResources, $tests, array('list', 'Default'));
        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(TestResource::RESOURCE_NAME);
        }
    }
}
