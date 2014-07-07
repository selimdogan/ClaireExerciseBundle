<?php
namespace SimpleIT\ClaireExerciseBundle\Controller\Api\CreatedExercise;

use SimpleIT\ClaireExerciseBundle\Controller\BaseController;
use SimpleIT\ClaireExerciseBundle\Exception\Api\ApiNotFoundException;
use SimpleIT\ClaireExerciseBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Model\Api\ApiGotResponse;
use SimpleIT\ClaireExerciseBundle\Model\Api\ApiResponse;
use SimpleIT\ClaireExerciseBundle\Model\Resources\AnswerResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\AnswerResourceFactory;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ItemResourceFactory;
use Symfony\Component\HttpFoundation\Request;

/**
 * API ItemByExercise controller
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class AnswerByItemController extends BaseController
{
    /**
     * List the answers fot this item
     *
     * @param $itemId
     *
     * @return ApiGotResponse
     * @throws ApiNotFoundException
     */
    public function listAction($itemId)
    {
        try {
            $answers = $this->get('simple_it.exercise.answer')->getAll(
                $itemId,
                null,
                $this->getUserIdIfNoCreator()
            );

            $answerResources = AnswerResourceFactory::createCollection($answers);

            return new ApiGotResponse($answerResources, array('list', 'Default'));
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
            $itemResource = $this->get('simple_it.exercise.item')
                ->findItemAndCorrectionById($itemId, $answerId);

            return new ApiGotResponse($itemResource, array('details', 'Default'));

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(AnswerResource::RESOURCE_NAME);
        }
    }
}
