<?php

namespace SimpleIT\ClaireExerciseBundle\Controller\Api\Test;

use SimpleIT\ApiBundle\Controller\ApiController;
use SimpleIT\ApiBundle\Exception\ApiBadRequestException;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ApiBundle\Model\ApiGotResponse;
use SimpleIT\ClaireExerciseBundle\Model\Resources\TestAttemptResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\TestAttemptResourceFactory;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\Utils\Collection\CollectionInformation;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TestAttemptController
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class TestAttemptController extends ApiController
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
            $testAttempt = $this->get('simple_it.exercise.test_attempt')->get($testAttemptId);
            $testAttemptResource = TestAttemptResourceFactory::create($testAttempt);

            return new ApiGotResponse($testAttemptResource, array("test_attempt", 'Default'));

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(TestAttemptResource::RESOURCE_NAME);
        }
    }

    /**
     * List the test attempts for this test
     *
     * @param Request               $request
     * @param CollectionInformation $collectionInformation
     *
     * @throws ApiBadRequestException
     * @throws ApiNotFoundException
     * @return ApiGotResponse
     */
    public function listAction(
        Request $request,
        CollectionInformation $collectionInformation
    )
    {
        try {
            $user = $request->get('user');
            $userId = null;
            if (is_null($user) || $user === 'me') {
                if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
                    throw new ApiBadRequestException("A user must be authenticated");
                }
                $userId = $this->get('security.context')->getToken()->getUser()->getId();
            } elseif ($user !== "all") {
                throw new ApiBadRequestException('Incorrect value for parameter user: ' . $user);
            }

            $testAttempts = $this->get('simple_it.exercise.test_attempt')->getAll
                (
                    $collectionInformation,
                    $userId
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
