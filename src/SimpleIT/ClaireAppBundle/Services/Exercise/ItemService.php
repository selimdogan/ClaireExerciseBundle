<?php

namespace SimpleIT\ClaireAppBundle\Services\Exercise;

use JMS\Serializer\SerializerInterface;
use SimpleIT\ApiResourcesBundle\Exercise\ItemResource;
use SimpleIT\ClaireAppBundle\Repository\Exercise\ItemByExerciseRepository;
use SimpleIT\ClaireAppBundle\Repository\Exercise\ItemRepository;
use SimpleIT\Utils\Collection\PaginatedCollection;

/**
 * Class ItemService
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ItemService implements ItemServiceInterface
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
     * @var  ExerciseService
     */
    private $exerciseService;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * Set serializer
     *
     * @param SerializerInterface $serializer
     */
    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Set exerciseService
     *
     * @param ExerciseService $exerciseService
     */
    public function setExerciseService($exerciseService)
    {
        $this->exerciseService = $exerciseService;
    }

    /**
     * Set itemByExerciseRepository
     *
     * @param ItemByExerciseRepository $itemByExerciseRepository
     */
    public function setItemByExerciseRepository($itemByExerciseRepository)
    {
        $this->itemByExerciseRepository = $itemByExerciseRepository;
    }

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
     * Get an item by its id
     *
     * @param int  $itemId Item Id
     * @param bool $showCorrection
     *
     * @return ItemResource
     */
    public function get($itemId, $showCorrection = false)
    {
        if ($showCorrection === true) {
            $parameters = array('showCorrection' => true);
        } else {
            $parameters = array();
        }

        return $this->itemRepository->find($itemId, $parameters);
    }

    /**
     * Get an item object form the id of the exercise and the number of the item in the exercise
     *
     * @param int  $exerciseId
     * @param int  $itemNumber
     * @param bool $corrected
     *
     * @return object The item object
     */
    public function getItemObjectFromExerciseAndItem($exerciseId, $itemNumber, &$corrected)
    {
        $itemResource = $this->getItemResourceFromExercise($exerciseId, $itemNumber);
        $corrected = $itemResource->getCorrected();

        return $this->getItemObjectFromResource($itemResource);
    }

    /**
     * Get an item object from a resource
     *
     * @param ItemResource $itemResource
     *
     * @return object The item object
     */
    public function getItemObjectFromResource(ItemResource $itemResource)
    {
        return $itemResource->getContent();
    }

    /**
     * Get ItemResource from exercise
     *
     * @param int $exerciseId
     * @param int $itemNumber
     *
     * @throws \OutOfBoundsException
     * @return ItemResource
     */
    public function getItemResourceFromExercise($exerciseId, $itemNumber)
    {
        try {
            $items = $this->getAllFromExercise($exerciseId);
            $keys = $items->getKeys();
            $itemId = $items->get($keys[$itemNumber - 1])->getItemId();

            return $this->get($itemId, true);
        } catch (\Exception $e) {
            throw new \OutOfBoundsException("Item not existing: exercise " . $exerciseId .
            " item " . $itemNumber);
        }

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
