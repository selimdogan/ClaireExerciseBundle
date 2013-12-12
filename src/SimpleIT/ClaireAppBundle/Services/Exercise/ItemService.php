<?php

namespace SimpleIT\ClaireAppBundle\Services\Exercise;

use JMS\Serializer\SerializerInterface;
use SimpleIT\ApiResourcesBundle\Exercise\Exercise\Common\CommonItem;
use SimpleIT\ApiResourcesBundle\Exercise\Exercise\GroupItems\Item as GroupItemsItem;
use SimpleIT\ApiResourcesBundle\Exercise\Exercise\MultipleChoice\Question;
use SimpleIT\ApiResourcesBundle\Exercise\Exercise\OrderItems\Item as OrderItemsItem;
use SimpleIT\ApiResourcesBundle\Exercise\Exercise\PairItems\Item as PairItemsItem;
use SimpleIT\ApiResourcesBundle\Exercise\ItemResource;
use SimpleIT\ClaireAppBundle\Repository\Exercise\Item\ItemByAttemptRepository;
use SimpleIT\ClaireAppBundle\Repository\Exercise\Item\ItemByExerciseRepository;
use SimpleIT\ClaireAppBundle\Repository\Exercise\Item\ItemRepository;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatedCollection;
use SimpleIT\Utils\Collection\Sort;

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
     * @var ItemByAttemptRepository
     */
    private $itemByAttemptRepository;

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
     * Set itemByAttemptRepository
     *
     * @param ItemByAttemptRepository $itemByAttemptRepository
     */
    public function setItemByAttemptRepository($itemByAttemptRepository)
    {
        $this->itemByAttemptRepository = $itemByAttemptRepository;
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
     * Get an item object from the id of the attempt and the id of the item
     *
     * @param int     $attemptId
     * @param int     $itemId
     * @param boolean $corrected
     *
     * @return object The item object
     */
    public function getItemObjectFromAttempt($attemptId, $itemId, &$corrected)
    {
        $item = $this->getByAttempt($attemptId, $itemId);
        $corrected = $item->getCorrected();
        $object = $item->getContent();

        if ($corrected) {
            $this->correctAndExplain($object);

            return $object;
        } else {
            return $object;
        }
    }

    /**
     * Get an item (corrected if it is answered) by its attempt and id
     *
     * @param $attemptId
     * @param $itemId
     *
     * @return ItemResource
     */
    public function getByAttempt($attemptId, $itemId)
    {
        return $this->itemByAttemptRepository->find($attemptId, $itemId);
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
     * Create the explanation message and sets the allRight data for the item
     *
     * @param CommonItem $object
     *
     * @throws \LogicException
     */
    private function correctAndExplain(CommonItem &$object)
    {
        switch (get_class($object)) {
            case ItemResource::MULTIPLE_CHOICE_CLASS:
                /** @var Question $object */
                $this->explainMultipleChoice($object);
                break;
            case ItemResource::GROUP_ITEMS_CLASS:
                /** @var GroupItemsItem $object */
                $this->explainGroupItems($object);
                break;
            case ItemResource::ORDER_ITEMS_CLASS:
                /** @var OrderItemsItem $object */
                $this->explainOrderItems($object);
                break;
            case ItemResource::PAIR_ITEMS_CLASS:
                /** @var PairItemsItem $object */
                $this->explainPairItems($object);
                break;
            default:
                throw new \LogicException('Unknown item class');
        }
    }

    /**
     * Create the explanation message and sets the allRight data for the MC item
     *
     * @param Question $item
     */
    private function explainMultipleChoice(Question &$item)
    {
        if ($item->getMark() == 100) {
            $item->setAllRight(true);
            $item->setExplanation('Bonne réponse');
        } else {
            $item->setAllRight(false);
            $item->setExplanation('Il y a des erreurs');
        }
    }

    /**
     * Create the explanation message and sets the allRight data for the GI item
     *
     * @param GroupItemsItem $item
     */
    private function explainGroupItems(GroupItemsItem &$item)
    {
        if ($item->getMark() == 100) {
            $item->setAllRight(true);
            $item->setExplanation('Bonne réponse');
        } else {
            $item->setAllRight(false);
            $item->setExplanation('Il y a des erreurs');
        }
    }

    /**
     * Create the explanation message and sets the allRight data for the OI item
     *
     * @param OrderItemsItem $item
     */
    private function explainOrderItems(OrderItemsItem &$item)
    {
        if ($item->getMark() == 100) {
            $item->setAllRight(true);
            $item->setExplanation('Bonne réponse');
        } else {
            $item->setAllRight(false);
            $item->setExplanation('Il y a des erreurs');
        }
    }

    /**
     * Create the explanation message and sets the allRight data for the PI item
     *
     * @param PairItemsItem $item
     */
    private function explainPairItems(PairItemsItem &$item)
    {
        if ($item->getMark() == 100) {
            $item->setAllRight(true);
            $item->setExplanation('Bonne réponse');
        } else {
            $item->setAllRight(false);
            $item->setExplanation('Il y a des erreurs');
        }
    }

    /**
     * Get all the items for this attempt
     *
     * @param int                   $attemptId
     * @param CollectionInformation $collectionInformation
     *
     * @return PaginatedCollection
     */
    public function getAll($attemptId, $collectionInformation = null)
    {
        return $this->itemByAttemptRepository->findAll($attemptId, $collectionInformation);
    }

    /**
     * Get the id of the first item of an attempt
     *
     * @param $attemptId
     *
     * @return mixed
     */
    public function getFirstItemId($attemptId)
    {
        $items = $this->getAll($attemptId);
        return $items[0]->getItemId();
    }
}
