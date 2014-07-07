<?php
namespace SimpleIT\ClaireExerciseBundle\Controller\Api\CreatedExercise;

use SimpleIT\ClaireExerciseBundle\Controller\Api\ApiController;
use SimpleIT\ClaireExerciseBundle\Exception\Api\ApiNotFoundException;
use SimpleIT\ClaireExerciseBundle\Model\Api\ApiGotResponse;
use SimpleIT\ClaireExerciseBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ItemResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ItemResourceFactory;
use SimpleIT\ClaireExerciseBundle\Model\Collection\CollectionInformation;

/**
 * API ItemByExercise controller
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ItemByAttemptController extends ApiController
{

    /**
     * View action. View an item with its solution. User's answer (is exists) is added inside to
     * make the correction possible.
     *
     * @param int $itemId
     * @param int $attemptId
     *
     * @throws ApiNotFoundException
     * @return ApiGotResponse
     */
    public function viewAction($itemId, $attemptId)
    {
        try {
            // Call to the item service to get the item and its correction if there is one
            $itemResource = $this->get('simple_it.exercise.item')
                ->findItemAndCorrectionByAttempt($itemId, $attemptId, $this->getUserId());

            return new ApiGotResponse($itemResource, array('details', 'Default'));

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(ItemResource::RESOURCE_NAME);
        }
    }

    /**
     * Get all items
     *
     * @param int $attemptId    Attempt id
     *
     * @throws ApiNotFoundException
     * @return ApiGotResponse
     */
    public function listAction($attemptId)
    {
        try {
            $itemResources = $this->get('simple_it.exercise.item')->getAllByAttempt(
                $attemptId,
                $this->getUserId()
            );

            return new ApiGotResponse($itemResources, array('details', 'Default'));
        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(ItemResource::RESOURCE_NAME);
        }
    }
}
