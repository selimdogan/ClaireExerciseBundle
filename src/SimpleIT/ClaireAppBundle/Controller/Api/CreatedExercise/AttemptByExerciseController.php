<?php
namespace SimpleIT\ExerciseBundle\Controller\CreatedExercise;

use SimpleIT\ApiBundle\Controller\ApiController;
use SimpleIT\ApiBundle\Exception\ApiBadRequestException;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ApiBundle\Model\ApiCreatedResponse;
use SimpleIT\ApiBundle\Model\ApiPaginatedResponse;
use SimpleIT\ApiBundle\Model\ApiResponse;
use SimpleIT\ApiResourcesBundle\Exercise\AnswerResource;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\ExerciseBundle\Exception\InvalidAnswerException;
use SimpleIT\ExerciseBundle\Model\Resources\AttemptResourceFactory;
use SimpleIT\Utils\Collection\CollectionInformation;
use Symfony\Component\HttpFoundation\Request;

/**
 * API AttemptByExercise controller
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class AttemptByExerciseController extends ApiController
{
    /**
     * List the attempts fot this exercise
     *
     * @param Request               $request
     * @param CollectionInformation $collectionInformation
     * @param int                   $exerciseId
     *
     * @throws ApiBadRequestException
     * @throws ApiNotFoundException
     * @return ApiPaginatedResponse
     */
    public function listAction(
        Request $request,
        CollectionInformation $collectionInformation,
        $exerciseId
    )
    {
        try {
            // get the user
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
                $userId,
                $exerciseId
            );

            $attemptResources = AttemptResourceFactory::createCollection($attempts);

            return new ApiPaginatedResponse($attemptResources, $attempts, array('list', 'Default'));
        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(AnswerResource::RESOURCE_NAME);
        }
    }

    /**
     * Create a new attempt for this exercise
     *
     * @param int $exerciseId
     *
     * @throws ApiBadRequestException
     * @throws ApiNotFoundException
     * @return ApiResponse
     */
    public function createAction($exerciseId)
    {
        try {
            // get the user
            if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
                throw new ApiBadRequestException("A user must be authenticated");
            }
            $userId = $this->get('security.context')->getToken()->getUser()->getId();

            $attempt = $this->get('simple_it.exercise.attempt')->add($exerciseId, $userId);

            $attemptResource = AttemptResourceFactory::create($attempt);

            return new ApiCreatedResponse($attemptResource, array("details", 'Default'));

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(AnswerResource::RESOURCE_NAME);
        } catch (InvalidAnswerException $iae) {
            throw new ApiBadRequestException(AnswerResource::RESOURCE_NAME);
        }
    }
}
