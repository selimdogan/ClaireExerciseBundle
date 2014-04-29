<?php

namespace SimpleIT\ClaireExerciseBundle\Service\Exercise\ExerciseCreation;

use SimpleIT\ClaireExerciseBundle\Model\Resources\AnswerResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\Exercise\Common\CommonExercise;
use SimpleIT\CoreBundle\Services\TransactionalService;
use SimpleIT\ClaireExerciseBundle\Entity\CreatedExercise\Answer;
use SimpleIT\ClaireExerciseBundle\Entity\CreatedExercise\Item;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\OwnerExerciseModel;
use SimpleIT\ClaireExerciseBundle\Service\Exercise\ExerciseModel\ExerciseModelServiceInterface;

/**
 * Service which manages the exercise generation
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ExerciseService extends TransactionalService implements ExerciseServiceInterface
{

    /**
     * @var  ExerciseModelServiceInterface
     */
    private $exerciseModelService;

    /**
     * @var ExerciseCreationServiceInterface
     */
    private $multipleChoiceService;

    /**
     * @var ExerciseCreationServiceInterface
     */
    private $pairItemsService;

    /**
     * @var ExerciseCreationServiceInterface
     */
    private $groupItemsService;

    /**
     * @var ExerciseCreationServiceInterface
     */
    private $orderItemsService;

    /**
     * @var ExerciseCreationServiceInterface
     */
    private $openEndedQuestionService;

    /**
     * Set ExerciseModelService
     *
     * @param ExerciseModelServiceInterface $exerciseModelService
     */
    public function setExerciseModelService(ExerciseModelServiceInterface $exerciseModelService)
    {
        $this->exerciseModelService = $exerciseModelService;
    }

    /**
     * Set MultipleChoiceService
     *
     * @param MultipleChoiceService $mcService
     */
    public function setMultipleChoiceService(MultipleChoiceService $mcService)
    {
        $this->multipleChoiceService = $mcService;
    }

    /**
     * Set PairItemsService
     *
     * @param PairItemsService $pairItemsService
     */
    public function setPairItemsService(PairItemsService $pairItemsService)
    {
        $this->pairItemsService = $pairItemsService;
    }

    /**
     * Set GroupItemsService
     *
     * @param GroupItemsService $groupItemsService
     */
    public function setGroupItemsService(GroupItemsService $groupItemsService)
    {
        $this->groupItemsService = $groupItemsService;
    }

    /**
     * Set OrderItemsService
     *
     * @param OrderItemsService $orderItemsService
     */
    public function setOrderItemsService(OrderItemsService $orderItemsService)
    {
        $this->orderItemsService = $orderItemsService;
    }

    /**
     * Set openEndedQuestionService
     *
     * @param OpenEndedQuestionService $openEndedQuestionService
     */
    public function setOpenEndedQuestionService($openEndedQuestionService)
    {
        $this->openEndedQuestionService = $openEndedQuestionService;
    }

    /**
     * Correct an item from an exercise
     *
     * @param Answer $answer Answer
     *
     * @return Item The corrected item
     * @throws \Exception
     */
    public function correctItem(Answer $answer)
    {
        $item = $answer->getItem();
        $type = $item->getType();

        // depending on the type of the item, call the service
        $service = $this->getServiceFromType($type);

        return $service->correct($item, $answer);
    }

    /**
     * Generate an exercise from the id of the model
     *
     * @param OwnerExerciseModel $ownerExerciseModel
     *
     * @return CommonExercise The generated exercise
     */
    public function generateExercise($ownerExerciseModel)
    {
        $emEntity = $this->exerciseModelService->get(
            $ownerExerciseModel->getExerciseModel()->getId()
        );
        $exerciseModel = $this->exerciseModelService->getModelFromEntity($emEntity);

        $service = $this->getServiceFromType($emEntity->getType());

        return $service->generateExerciseFromExerciseModel(
            $ownerExerciseModel,
            $exerciseModel,
            $ownerExerciseModel->getOwner()
        );
    }

    /**
     * Validate learner's answer format
     *
     * @param Item           $item
     * @param AnswerResource $answerResource
     */
    public function validateAnswer(Item $item, AnswerResource $answerResource)
    {
        $service = $this->getServiceFromType($item->getType());
        $service->validateAnswer($item, $answerResource->getContent());
    }

    /**
     * Select the right exercise service according to the type
     *
     * @param string $type
     *
     * @return ExerciseCreationServiceInterface
     * @throws \LogicException
     */
    private function getServiceFromType($type)
    {
        $service = null;
        switch ($type) {
            case CommonExercise::MULTIPLE_CHOICE:
                $service = $this->multipleChoiceService;
                break;
            case CommonExercise::PAIR_ITEMS:
                $service = $this->pairItemsService;
                break;
            case CommonExercise::GROUP_ITEMS:
                $service = $this->groupItemsService;
                break;
            case CommonExercise::ORDER_ITEMS:
                $service = $this->orderItemsService;
                break;
            case CommonExercise::OPEN_ENDED_QUESTION:
                $service = $this->openEndedQuestionService;
                break;
            case null:
                throw new \LogicException('The type of exercise should be specified');
            default :
                throw new \DomainException('Unknown type of item');

        }

        return $service;
    }
}
