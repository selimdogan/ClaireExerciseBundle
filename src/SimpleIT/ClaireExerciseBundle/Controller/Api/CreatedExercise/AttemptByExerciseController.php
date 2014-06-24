<?php
namespace SimpleIT\ClaireExerciseBundle\Controller\Api\CreatedExercise;

use SimpleIT\ClaireExerciseBundle\Controller\Api\ApiController;
use SimpleIT\ClaireExerciseBundle\Exception\Api\ApiBadRequestException;
use SimpleIT\ClaireExerciseBundle\Exception\Api\ApiNotFoundException;
use SimpleIT\ClaireExerciseBundle\Model\Api\ApiCreatedResponse;
use SimpleIT\ClaireExerciseBundle\Model\Api\ApiGotResponse;
use SimpleIT\ClaireExerciseBundle\Model\Api\ApiResponse;
use SimpleIT\ClaireExerciseBundle\Exception\InvalidAnswerException;
use SimpleIT\ClaireExerciseBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\AnswerResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\AttemptResourceFactory;
use SimpleIT\ClaireExerciseBundle\Model\Collection\CollectionInformation;
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
     * @return ApiGotResponse
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
                $userId = $this->getUserId();
            } elseif ($user !== "all") {
                throw new ApiBadRequestException('Incorrect value for parameter user: ' . $user);
            }

            $attempts = $this->get('simple_it.exercise.attempt')->getAll(
                $collectionInformation,
                $userId,
                $exerciseId
            );

            $attemptResources = AttemptResourceFactory::createCollection($attempts);

            return new ApiGotResponse($attemptResources, array('list', 'Default'));
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
            $userId = $this->getUserId();

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
