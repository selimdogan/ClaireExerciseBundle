<?php
namespace SimpleIT\ExerciseBundle\Controller\CreatedExercise;

use SimpleIT\ApiBundle\Controller\ApiController;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ApiBundle\Model\ApiPaginatedResponse;
use SimpleIT\ApiResourcesBundle\Exercise\ItemResource;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\ExerciseBundle\Model\Resources\ItemResourceFactory;

/**
 * API ItemByExercise controller
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ItemByExerciseController extends ApiController
{
    /**
     * Get all items
     *
     * @param int $exerciseId    Exercise id
     *
     * @throws ApiNotFoundException
     * @return ApiPaginatedResponse
     */
    public function listAction($exerciseId)
    {
        try {
            $items = $this->get('simple_it.exercise.item')->getAll($exerciseId);

            $itemResources = ItemResourceFactory::createCollection($items);

            return new ApiPaginatedResponse($itemResources, $items, array('list', 'Default'));
        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(ItemResource::RESOURCE_NAME);
        }
    }
}
