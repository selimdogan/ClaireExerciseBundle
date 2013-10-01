<?php

namespace SimpleIT\ClaireAppBundle\Services\Exercise;

use SimpleIT\ApiResourcesBundle\Exercise\AnswerResource;
use SimpleIT\ApiResourcesBundle\Exercise\Exercise\GroupItems\Item as GroupItemsItem;
use SimpleIT\ApiResourcesBundle\Exercise\Exercise\MultipleChoice\Question;
use SimpleIT\ApiResourcesBundle\Exercise\Exercise\OrderItems\Item as OrderItemsItem;
use SimpleIT\ApiResourcesBundle\Exercise\Exercise\PairItems\Item as PairItemsItem;
use SimpleIT\ApiResourcesBundle\Exercise\ItemResource;
use SimpleIT\ClaireAppBundle\Model\Exercise\AnswerResourceFactory;
use SimpleIT\ClaireAppBundle\Repository\Exercise\AnswerByItemRepository;

/**
 * Class AnswerService
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class AnswerService implements AnswerServiceInterface
{
    /**
     * @const HTML_CHECKBOX_SELECTED = 'on'
     */
    const HTML_CHECKBOX_SELECTED = 'on';

    /**
     * @var  AnswerByItemRepository
     */
    private $answerByItemRepository;

    /**
     * @var  ItemServiceInterface
     */
    private $itemService;

    /**
     * Set answerByItemRepository
     *
     * @param AnswerByItemRepository $answerRepository
     */
    public function setAnswerByItemRepository(AnswerByItemRepository $answerRepository)
    {
        $this->answerByItemRepository = $answerRepository;
    }

    /**
     * Set itemService
     *
     * @param ItemServiceInterface $itemService
     */
    public function setItemService($itemService)
    {
        $this->itemService = $itemService;
    }

    /**
     * Add a new Learner Answer to an item
     *
     * @param int   $exerciseId
     * @param int   $itemNumber
     * @param array $answers
     *
     * @internal param int $itemId
     * @return AnswerResource
     */
    public function add($exerciseId, $itemNumber, array $answers)
    {
        $itemRes = $this->itemService->getItemResourceFromExercise($exerciseId, $itemNumber);

        $answerResource = $this->getResourceFromAnswer($answers, $itemRes);

        $answerResource = $this->answerByItemRepository->insertResource(
            $answerResource,
            array('itemId' => $itemRes->getItemId())
        );

        return $answerResource;
    }

    /**
     * Create an answerResource from an array of answer an from the item resource
     *
     * @param array        $la
     * @param ItemResource $itemRes
     *
     * @return mixed
     * @throws \LogicException
     */
    private function getResourceFromAnswer(array $la, ItemResource $itemRes)
    {
        $item = $this->itemService->getItemObjectFromResource($itemRes);

        switch (get_class($item)) {
            case ItemResource::MULTIPLE_CHOICE_CLASS:
                /** @var Question $item */
                $answerResource = $this->getResourceFromMultipleChoiceAnswer($item, $la);
                break;
            case ItemResource::GROUP_ITEMS_CLASS:
                /** @var GroupItemsItem $item */
                $answerResource = $this->getResourceFromGroupItemsAnswer($item, $la);
                break;
            case ItemResource::ORDER_ITEMS_CLASS:
                /** @var OrderItemsItem $item */
                $answerResource = $this->getResourceFromOrderItemsAnswer($item, $la);
                break;
            case ItemResource::PAIR_ITEMS_CLASS:
                /** @var PairItemsItem $item */
                $answerResource = $this->getResourceFromPairItemsAnswer($item, $la);
                break;
            default:
                throw new \LogicException('Unknown type of answer');
        }

        return $answerResource;
    }

    /**
     * Create an answerResource from an array an a MultipleChoice Question
     *
     * @param Question $item
     * @param array    $answers
     *
     * @return AnswerResource
     */
    private function getResourceFromMultipleChoiceAnswer(Question $item, array $answers)
    {
        $numberOfQuestions = count($item->getPropositions());
        $array = array();

        for ($i = 0; $i < $numberOfQuestions; $i++) {
            if (isset($answers[$i]) && $answers[$i] === self::HTML_CHECKBOX_SELECTED) {
                $array[$i] = 1;
            } else {
                $array[$i] = 0;
            }
        }

        $answerRes = AnswerResourceFactory::create($array);

        return $answerRes;
    }

    /**
     * Create an answerResource from an array a group Items AnswerResource
     *
     * @param GroupItemsItem $item
     * @param array          $answers
     *
     * @return AnswerResource
     */
    private function getResourceFromGroupItemsAnswer(GroupItemsItem $item, array $answers)
    {
        $numberOfObjects = count($item->getObjects());
        if ($item->getDisplayGroupNames() === GroupItemsItem::ASK) {
            $numberOfGroupsToName = count($item->getGroups());
        } else {
            $numberOfGroupsToName = null;
        }

        $array = array();
        $obj = array();

        for ($i = 0; $i < $numberOfObjects; $i++) {
            if (isset($answers['obj'][$i])) {
                $obj[$i] = $answers['obj'][$i];
            } else {
                $obj[$i] = null;
            }
        }
        $array['obj'] = $obj;

        if (!is_null($numberOfGroupsToName)) {
            $gr = array();
            for ($i = 0; $i < $numberOfGroupsToName; $i++) {
                if (isset($answers['gr'][$i])) {
                    $gr[$i] = $answers['gr'][$i];
                } else {
                    $gr[$i] = null;
                }
            }
            $array['gr'] = $gr;
        }

        $answerRes = AnswerResourceFactory::create($array);

        return $answerRes;
    }

    /**
     * Create an answerResource from an array an order Items AnswerResource
     *
     * @param OrderItemsItem $item
     * @param array          $answers
     *
     * @return AnswerResource
     */
    private function getResourceFromOrderItemsAnswer(OrderItemsItem $item, array $answers)
    {
        $numberOfObjects = count($item->getObjects());

        $array = array();

        for ($i = 0; $i < $numberOfObjects; $i++) {
            if (isset($answers[$i])) {
                $array[$i] = $answers[$i];
            } else {
                $array[$i] = null;
            }
        }

        $answerRes = AnswerResourceFactory::create($array);

        return $answerRes;
    }

    /**
     * Create an answerResource from an array an Pair Items AnswerResource
     *
     * @param PairItemsItem $item
     * @param array         $answers
     *
     * @return AnswerResource
     */
    private function getResourceFromPairItemsAnswer(PairItemsItem $item, array $answers)
    {
        $numberOfObjects = count($item->getLeftParts());

        $array = array();

        for ($i = 0; $i < $numberOfObjects; $i++) {
            if (isset($answers[$i])) {
                $array[$i] = $answers[$i];
            } else {
                $array[$i] = null;
            }
        }

        $answerRes = AnswerResourceFactory::create($array);

        return $answerRes;
    }

}
