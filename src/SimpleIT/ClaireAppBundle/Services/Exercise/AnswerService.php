<?php

namespace SimpleIT\ClaireAppBundle\Services\Exercise;

use SimpleIT\ApiResourcesBundle\Exercise\AnswerResource;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseCreation\Common\CommonExercise;
use
    SimpleIT\ApiResourcesBundle\Exercise\ExerciseCreation\MultipleChoice\Question as MultipleChoiceItem;
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

        switch ($itemRes->getType()) {
            case CommonExercise::MULTIPLE_CHOICE:
                /** @var MultipleChoiceItem $item */
                $answerResource = $this->getResourceFromMultipleChoiceAnswer($item, $la);
                break;
            default:
                throw new \LogicException('Unknown type of answer');
        }

        return $answerResource;
    }

    /**
     * Create an answerResource from an array an a MultipleChoice Question
     *
     * @param MultipleChoiceItem $item
     * @param array              $answers
     *
     * @return AnswerResource
     */
    private function getResourceFromMultipleChoiceAnswer(MultipleChoiceItem $item, array $answers)
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

}
