<?php

namespace SimpleIT\ClaireExerciseBundle\Controller\Api\Test;

use SimpleIT\ClaireExerciseBundle\Controller\BaseController;
use SimpleIT\ClaireExerciseBundle\Exception\Api\ApiBadRequestException;
use SimpleIT\ClaireExerciseBundle\Exception\Api\ApiNotFoundException;
use SimpleIT\ClaireExerciseBundle\Model\Api\ApiGotResponse;
use SimpleIT\ClaireExerciseBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\TestAttemptResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\TestAttemptResourceFactory;
use SimpleIT\ClaireExerciseBundle\Model\Collection\CollectionInformation;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TestAttemptController
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class TestAttemptController extends BaseController
{
    /**
     * Get a specific Attempt resource
     *
     * @param int $testAttemptId
     *
     * @throws ApiNotFoundException
     * @return ApiGotResponse
     */
    public function viewAction($testAttemptId)
    {
        try {
            $testAttempt = $this->get('simple_it.exercise.test_attempt')->get(
                $testAttemptId,
                $this->getUserIdIfNoCreator()
            );
            $testAttemptResource = TestAttemptResourceFactory::create($testAttempt);

            return new ApiGotResponse($testAttemptResource, array("test_attempt", 'Default'));

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(TestAttemptResource::RESOURCE_NAME);
        }
    }

    /**
     * List the test attempts for this test
     *
     * @param CollectionInformation $collectionInformation
     *
     * @throws ApiBadRequestException
     * @throws ApiNotFoundException
     * @return ApiGotResponse
     */
    public function listAction(
        CollectionInformation $collectionInformation
    )
    {
        try {
            $testAttempts = $this->get('simple_it.exercise.test_attempt')->getAll
                (
                    $collectionInformation,
                    $this->getUserIdIfNoCreator()
                );

            $testAttemptResources = TestAttemptResourceFactory::createCollection($testAttempts);

            return new ApiGotResponse($testAttemptResources, array(
                'list',
                'Default'
            ));
        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(TestAttemptResource::RESOURCE_NAME);
        }
    }
}
