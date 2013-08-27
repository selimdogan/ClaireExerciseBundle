<?php


namespace SimpleIT\ClaireAppBundle\Services\Exercise;

use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource;
use SimpleIT\ApiResourcesBundle\Exercise\ItemResource;
use SimpleIT\ClaireAppBundle\Repository\Exercise\ItemByExerciseRepository;
use SimpleIT\ClaireAppBundle\Repository\Exercise\ItemRepository;
use SimpleIT\Utils\Collection\PaginatedCollection;

/**
 * Class ItemService
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ItemService
{
    /**
     * @var  ItemRepository
     */
    private $itemRepository;

    /**
     * @var  ItemByExerciseRepository
     */
    private $itemByExerciseRepository;

    /**
     * Set ItemRepository
     *
     * @param ItemRepository $itemRepository
     */
    public function setItemRepository(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    /**
     * Get an item
     *
     * @param int $itemId Item Id
     *
     * @return ItemResource
     */
    public function get($itemId)
    {
        return $this->itemRepository->find($itemId);
    }

    /**
     * Get all the items of an exercise
     *
     * @param int $exerciseId
     *
     * @return PaginatedCollection
     */
    public function getAllFromExercise($exerciseId)
    {
        return $this->itemByExerciseRepository->findAllResources(
            array(
                'exerciseId' => $exerciseId
            )
        );
    }
}
