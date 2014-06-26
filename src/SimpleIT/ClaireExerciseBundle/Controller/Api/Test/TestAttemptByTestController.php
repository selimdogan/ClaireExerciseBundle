<?php

namespace SimpleIT\ClaireExerciseBundle\Controller\Api\Test;

use SimpleIT\ClaireExerciseBundle\Controller\Api\ApiController;
use SimpleIT\ClaireExerciseBundle\Exception\Api\ApiBadRequestException;
use SimpleIT\ClaireExerciseBundle\Exception\Api\ApiNotFoundException;
use SimpleIT\ClaireExerciseBundle\Model\Api\ApiCreatedResponse;
use SimpleIT\ClaireExerciseBundle\Model\Api\ApiGotResponse;
use SimpleIT\ClaireExerciseBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\TestAttemptResourceFactory;
use SimpleIT\ClaireExerciseBundle\Model\Resources\TestResource;
use SimpleIT\ClaireExerciseBundle\Model\Collection\CollectionInformation;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TestAttemptByTestController
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class TestAttemptByTestController extends ApiController
{
    /**
     * Create a test attempt for a test
     *
     * @param int $testId
     *
     * @return ApiCreatedResponse
     * @throws ApiBadRequestException
     * @throws ApiNotFoundException
     */
    public function createAction($testId)
    {
        try {
            $testAttempt = $this->get('simple_it.exercise.test_attempt')->add(
                $testId,
                $this->getUserId()
            );

            $testAttemptResource = TestAttemptResourceFactory::create($testAttempt);

            return new ApiCreatedResponse($testAttemptResource, array('details', 'Default'));

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(TestResource::RESOURCE_NAME);
        }
    }

    /**
     * List the test attempts for this test
     *
     * @param CollectionInformation $collectionInformation
     * @param int $testId
     *
     * @throws ApiBadRequestException
     * @throws ApiNotFoundException
     * @return ApiGotResponse
     */
    public function listAction(
        CollectionInformation $collectionInformation,
        $testId
    )
    {
        try {
            $testAttempts = $this->get('simple_it.exercise.test_attempt')->getAll(
                $collectionInformation,
                $this->getUserIdIfNoCreator(),
                $testId
            );

            $testAttemptResources = TestAttemptResourceFactory::createCollection($testAttempts);

            return new ApiGotResponse($testAttemptResources, array(
                'list',
                'Default'
            ));
        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(TestResource::RESOURCE_NAME);
        }
    }
}
