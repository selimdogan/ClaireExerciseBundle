<?php

namespace SimpleIT\ClaireAppBundle\Services\Exercise;

use JMS\Serializer\SerializerInterface;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseCreation\Common\CommonExercise;
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
class ItemService implements ItemServiceInterface
{
    const MULTIPLE_CHOICE_CLASS = 'SimpleIT\ApiResourcesBundle\Exercise\ExerciseCreation\MultipleChoice\Question';

    const PAIR_ITEMS_CLASS = 'SimpleIT\ApiResourcesBundle\Exercise\ExerciseCreation\PairItems\Exercise';

    const ORDER_ITEMS_CLASS = 'SimpleIT\ApiResourcesBundle\Exercise\ExerciseCreation\OrderItems\Exercise';

    const GROUP_ITEMS_CLASS = 'SimpleIT\ApiResourcesBundle\Exercise\ExerciseCreation\GroupItems\Exercise';

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
        $class = $this->getClassFromType($itemResource->getType());

        return $this->serializer->deserialize($itemResource->getContent(), $class, 'json');
    }

    /**
     * Get ItemResource from exercise
     *
     * @param int $exerciseId
     * @param int $itemNumber
     *
     * @return ItemResource
     */
    public function getItemResourceFromExercise($exerciseId, $itemNumber)
    {
        $items = $this->getAllFromExercise($exerciseId);
        $keys = $items->getKeys();
        $itemId = $items->get($keys[$itemNumber - 1])->getItemId();

        return $this->get($itemId);
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

    /**
     * Get the serialization class of the item from the type of the exercise
     *
     * @param string $type
     *
     * @return string
     * @throws \LogicException
     */
    private function getClassFromType($type)
    {
        switch ($type) {
            case CommonExercise::MULTIPLE_CHOICE:
                $class = self::MULTIPLE_CHOICE_CLASS;
                break;
            case CommonExercise::GROUP_ITEMS:
                $class = self::GROUP_ITEMS_CLASS;
                break;
            case CommonExercise::PAIR_ITEMS:
                $class = self::PAIR_ITEMS_CLASS;
                break;
            case CommonExercise::ORDER_ITEMS:
                $class = self::ORDER_ITEMS_CLASS;
                break;
            default:
                throw new \LogicException('Unknown type of exercise: ' . $type);
        }

        return $class;
    }
}
