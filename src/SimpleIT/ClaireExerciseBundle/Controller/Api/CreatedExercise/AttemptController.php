<?php
namespace SimpleIT\ClaireExerciseBundle\Controller\Api\CreatedExercise;

use SimpleIT\ApiBundle\Controller\ApiController;
use SimpleIT\ApiBundle\Exception\ApiBadRequestException;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ApiBundle\Model\ApiGotResponse;
use SimpleIT\ApiBundle\Model\ApiResponse;
use SimpleIT\ClaireExerciseBundle\Model\Resources\AttemptResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\AttemptResourceFactory;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\Utils\Collection\CollectionInformation;
use Symfony\Component\HttpFoundation\Request;

/**
 * API Attempt controller
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class AttemptController extends ApiController
{
    /**
     * Get a specific Attempt resource
     *
     * @param int $attemptId
     *
     * @throws ApiNotFoundException
     * @return ApiGotResponse
     */
    public function viewAction($attemptId)
    {
        try {
            $attempt = $this->get('simple_it.exercise.attempt')->get($attemptId);
            $attemptResource = AttemptResourceFactory::create($attempt);

            return new ApiGotResponse($attemptResource, array("attempt", 'Default'));

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(AttemptResource::RESOURCE_NAME);
        }
    }

    /**
     * Get the list of attempts
     *
     * @param Request               $request
     * @param CollectionInformation $collectionInformation
     *
     * @throws ApiBadRequestException
     * @return ApiGotResponse
     */
    public function listAction(Request $request, CollectionInformation $collectionInformation)
    {
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

        $attempts = $this->get('simple_it.exercise.attempt')->getAll(
            $collectionInformation,
            $userId
        );

        $attemptsResources = AttemptResourceFactory::createCollection($attempts);

        return new ApiGotResponse($attemptsResources, array('list', 'Default'));
    }
}
