<?php
namespace SimpleIT\ExerciseBundle\Controller\CreatedExercise;

use SimpleIT\ApiBundle\Controller\ApiController;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ApiBundle\Model\ApiGotResponse;
use SimpleIT\ApiBundle\Model\ApiPaginatedResponse;
use SimpleIT\ApiResourcesBundle\Exercise\ItemResource;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\ExerciseBundle\Model\Resources\ItemResourceFactory;
use SimpleIT\Utils\Collection\CollectionInformation;

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
            $corrected = false;
            $item = $this->get('simple_it.exercise.item')
                ->findItemAndCorrectionByAttempt($itemId, $attemptId, $corrected);

            $itemResource = ItemResourceFactory::create($item, $corrected);

            if ($corrected) {
                $groups = array("corrected", 'Default');
            } else {
                $groups = array("not_corrected", 'Default');
            }

            return new ApiGotResponse($itemResource, $groups);

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(ItemResource::RESOURCE_NAME);
        }
    }

    /**
     * Get all items
     *
     * @param CollectionInformation $collectionInformation
     * @param int                   $attemptId    Attempt id
     *
     * @throws ApiNotFoundException
     * @return ApiPaginatedResponse
     */
    public function listAction(CollectionInformation $collectionInformation, $attemptId)
    {
        try {
            $items = $this->get('simple_it.exercise.item')->getAllByAttempt(
                $collectionInformation,
                $attemptId
            );

            $itemResources = ItemResourceFactory::createCollection($items);

            return new ApiPaginatedResponse($itemResources, $items, array('list', 'Default'));
        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(ItemResource::RESOURCE_NAME);
        }
    }
}
