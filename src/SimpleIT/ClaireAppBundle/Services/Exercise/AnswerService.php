<?php

namespace SimpleIT\ClaireAppBundle\Services\Exercise;

use SimpleIT\ApiResourcesBundle\Exercise\ExerciseCreation\Common\LearnerAnswer;
use SimpleIT\ClaireAppBundle\Model\Exercise\MultipleChoice\LearnerAnswerFactory;
use SimpleIT\ClaireAppBundle\Repository\Exercise\AnswerByItemRepository;

/**
 * Class AnswerService
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class AnswerService implements AnswerServiceInterface
{
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
     * @param array $la
     * @param array $options
     *
     * @internal param int $itemId
     * @return LearnerAnswer
     */
    public function add($exerciseId, $itemNumber, array $la, array $options)
    {
        $itemRes = $this->itemService->getItemResourceFromExercise($exerciseId, $itemNumber);
        $la = LearnerAnswerFactory::create($la, $options);

        $la = $this->answerByItemRepository->insertResource(
            $la,
            array('itemId' => $itemRes->getItemId())
        );

        return $la;
    }

}
