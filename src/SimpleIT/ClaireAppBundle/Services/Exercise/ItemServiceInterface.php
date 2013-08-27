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
     * Get all the items of an exercise
     *
     * @param int $exerciseId
     *
     * @return PaginatedCollection
     */
    public function getAllFromExercise($exerciseId);
}
