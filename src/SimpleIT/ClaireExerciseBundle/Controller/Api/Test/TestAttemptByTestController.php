<?php

namespace SimpleIT\ClaireExerciseBundle\Controller\Api\Test;

use SimpleIT\ApiBundle\Controller\ApiController;
use SimpleIT\ApiBundle\Exception\ApiBadRequestException;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ApiBundle\Model\ApiCreatedResponse;
use SimpleIT\ApiBundle\Model\ApiPaginatedResponse;
use SimpleIT\ClaireExerciseBundle\Model\Resources\TestResource;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\TestAttemptResourceFactory;
use SimpleIT\Utils\Collection\CollectionInformation;
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
            // get the user
            if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
                throw new ApiBadRequestException("A user must be authenticated");
            }
            $userId = $this->get('security.context')->getToken()->getUser()->getId();

            $testAttempt = $this->get('simple_it.exercise.test_attempt')->add($testId, $userId);

            $testAttemptResource = TestAttemptResourceFactory::create($testAttempt);

            return new ApiCreatedResponse($testAttemptResource, array('details', 'Default'));

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(TestResource::RESOURCE_NAME);
        }
    }

    /**
     * List the test attempts for this test
     *
     * @param Request               $request
     * @param CollectionInformation $collectionInformation
     * @param int                   $testId
     *
     * @throws ApiBadRequestException
     * @throws ApiNotFoundException
     * @return ApiPaginatedResponse
     */
    public function listAction(
        Request $request,
        CollectionInformation $collectionInformation,
        $testId
    )
    {
        try {
            // get the user
            $concernedUser = $request->get('user');

            $userId = null;
            if (is_null($concernedUser) || $concernedUser === 'me') {
                if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
                    throw new ApiBadRequestException("A user must be authenticated");
                }
                $userId = $this->get('security.context')->getToken()->getUser()->getId();
            } elseif ($concernedUser !== "all") {
                throw new ApiBadRequestException('Incorrect value for parameter user: '
                . $concernedUser);
            }

            $testAttempts = $this->get('simple_it.exercise.test_attempt')->getAll(
                $collectionInformation,
                $userId,
                $testId
            );

            $testAttemptResources = TestAttemptResourceFactory::createCollection($testAttempts);

            return new ApiPaginatedResponse($testAttemptResources, $testAttempts, array(
                'list',
                'Default'
            ));
        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(TestResource::RESOURCE_NAME);
        }
    }
}
