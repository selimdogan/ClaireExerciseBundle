<?php
namespace SimpleIT\ExerciseBundle\Controller\CreatedExercise;

use SimpleIT\ApiBundle\Controller\ApiController;
use SimpleIT\ApiBundle\Exception\ApiBadRequestException;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ApiBundle\Model\ApiCreatedResponse;
use SimpleIT\ApiBundle\Model\ApiGotResponse;
use SimpleIT\ApiBundle\Model\ApiPaginatedResponse;
use SimpleIT\ApiBundle\Model\ApiResponse;
use SimpleIT\ApiResourcesBundle\Exercise\AnswerResource;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\ExerciseBundle\Exception\InvalidAnswerException;
use SimpleIT\ExerciseBundle\Model\Resources\AnswerResourceFactory;
use SimpleIT\ExerciseBundle\Model\Resources\ItemResourceFactory;
use Symfony\Component\HttpFoundation\Request;

/**
 * API ItemByExercise controller
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class AnswerByItemController extends ApiController
{
    /**
     * List the answers fot this item
     *
     * @param $itemId
     *
     * @return ApiPaginatedResponse
     * @throws ApiNotFoundException
     */
    public function listAction($itemId)
    {
        try {
            $answers = $this->get('simple_it.exercise.answer')->getAll($itemId);

            $answerResources = AnswerResourceFactory::createCollection($answers);

            return new ApiPaginatedResponse($answerResources, $answers, array('list', 'Default'));
        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(AnswerResource::RESOURCE_NAME);
        }
    }

    /**
     * View action. View an item with its solution and the user's answer.
     *
     * @param $itemId
     * @param $answerId
     *
     * @throws ApiNotFoundException
     * @return ApiGotResponse
     */
    public function viewAction($itemId, $answerId)
    {
        try {
            // Call to the item service to get the item and its correction if there is one
            $itemEntity = $this->get('simple_it.exercise.item')
                ->findItemAndCorrectionById($itemId, $answerId);

            $itemResource = ItemResourceFactory::create($itemEntity, true);

            return new ApiGotResponse($itemResource, array('details', 'Default'));

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(AnswerResource::RESOURCE_NAME);
        }
    }

    /**
     * Answer action. Create an answer for the given stored exercise.
     *
     * @param int            $itemId
     * @param AnswerResource $answerResource
     *
     * @throws ApiBadRequestException
     * @throws ApiNotFoundException
     * @return ApiResponse
     */
    public function createAction($itemId, AnswerResource $answerResource)
    {
        try {
            $this->validateResource($answerResource, array('create', 'Default'));

            // send to the answer service in order to create the answer
            $answer = $this->get('simple_it.exercise.answer')
                ->add($itemId, $answerResource);

            $answerResource = AnswerResourceFactory::create($answer);

            return new ApiCreatedResponse($answerResource, array("details", 'Default'));

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(AnswerResource::RESOURCE_NAME);
        } catch (InvalidAnswerException $iae) {
            throw new ApiBadRequestException(AnswerResource::RESOURCE_NAME);
        }
    }
}
