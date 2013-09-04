<?php

namespace SimpleIT\ClaireAppBundle\Services\Exercise;

use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource;
use SimpleIT\ApiResourcesBundle\Exercise\ItemResource;
use SimpleIT\ClaireAppBundle\Repository\Exercise\ItemByExerciseRepository;
use SimpleIT\ClaireAppBundle\Repository\Exercise\ItemRepository;
use SimpleIT\Utils\Collection\PaginatedCollection;

/**
 * Interface for ItemService class
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
interface ItemServiceInterface
{
    /**
     * Get an item
     *
     * @param int $itemId Item Id
     *
     * @return ItemResource
     */
    public function get($itemId);

    /**
     * Get an item object form the id of the exercise and the number of the item in the exercise
     *
     * @param int     $exerciseId
     * @param int     $itemNumber
     * @param boolean $corrected
     *
     * @return object The item object
     */
    public function getItemObjectFromExerciseAndItem($exerciseId, $itemNumber, &$corrected);

    /**
     * Get ItemResource from exercise
     *
     * @param int $exerciseId
     * @param int $itemNumber
     *
     * @return ItemResource
     */
    public function getItemResourceFromExercise($exerciseId, $itemNumber);

    /**
     * Get all the items of an exercise
     *
     * @param int $exerciseId
     *
     * @return PaginatedCollection
     */
    public function getAllFromExercise($exerciseId);
}
