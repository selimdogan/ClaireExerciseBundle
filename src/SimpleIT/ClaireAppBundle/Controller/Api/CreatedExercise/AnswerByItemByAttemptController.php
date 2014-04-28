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
use SimpleIT\ExerciseBundle\Exception\AnswerAlreadyExistsException;
use SimpleIT\ExerciseBundle\Exception\InvalidAnswerException;
use SimpleIT\ExerciseBundle\Model\Resources\AnswerResourceFactory;

/**
 * API AnswerByItemByAttempt Controller
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class AnswerByItemByAttemptController extends ApiController
{
    /**
     * List the answers fot this item
     *
     * @param int $attemptId
     * @param int $itemId
     *
     * @throws ApiNotFoundException
     * @return ApiPaginatedResponse
     */
    public function listAction($attemptId, $itemId)
    {
        try {
            $answers = $this->get('simple_it.exercise.answer')->getAll($itemId, $attemptId);

            $answerResources = AnswerResourceFactory::createCollection($answers);

            return new ApiPaginatedResponse($answerResources, $answers, array('list', 'Default'));
        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(AnswerResource::RESOURCE_NAME);
        }
    }

    /**
     * Answer action. Create an answer for the given stored exercise.
     *
     * @param int            $attemptId
     * @param int            $itemId
     * @param AnswerResource $answerResource
     *
     * @throws ApiBadRequestException
     * @throws ApiNotFoundException
     * @return ApiResponse
     */
    public function createAction($attemptId, $itemId, AnswerResource $answerResource)
    {
        try {
            $this->validateResource($answerResource, array('create', 'Default'));

            // send to the answer service in order to create the answer
            $answer = $this->get('simple_it.exercise.answer')
                ->add($itemId, $answerResource, $attemptId);

            $answerResource = AnswerResourceFactory::create($answer);

            return new ApiCreatedResponse($answerResource, array("details", 'Default'));

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(AnswerResource::RESOURCE_NAME);
        } catch (InvalidAnswerException $iae) {
            throw new ApiBadRequestException(AnswerResource::RESOURCE_NAME);
        } catch (AnswerAlreadyExistsException $aaee) {
            throw new ApiBadRequestException($aaee->getMessage());
        }
    }
}
