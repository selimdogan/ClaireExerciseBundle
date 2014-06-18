<?php

namespace SimpleIT\ClaireExerciseBundle\Service\Exercise\CreatedExercise;

use SimpleIT\ClaireExerciseBundle\Model\Resources\ItemResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ItemResourceFactory;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\CoreBundle\Services\TransactionalService;
use SimpleIT\ClaireExerciseBundle\Entity\CreatedExercise\Answer;
use SimpleIT\ClaireExerciseBundle\Entity\CreatedExercise\Item;
use SimpleIT\ClaireExerciseBundle\Repository\Exercise\CreatedExercise\AnswerRepository;
use SimpleIT\ClaireExerciseBundle\Repository\Exercise\CreatedExercise\ItemRepository;
use SimpleIT\ClaireExerciseBundle\Service\Exercise\ExerciseCreation\ExerciseServiceInterface;
use SimpleIT\Utils\Collection\CollectionInformation;


/**
 * Service which manages the items
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ItemService extends TransactionalService implements ItemServiceInterface
{
    /**
     * @var ItemRepository
     */
    private $itemRepository;

    /**
     * @var AnswerRepository
     */
    private $answerRepository;

    /**
     * @var ExerciseServiceInterface
     */
    private $exerciseService;

    /**
     * @var  StoredExerciseService
     */
    private $storedExerciseService;

    /**
     * @var AttemptServiceInterface
     */
    private $attemptService;

    /**
     * Set storedExerciseService
     *
     * @param StoredExerciseService $storedExerciseService
     */
    public function setStoredExerciseService($storedExerciseService)
    {
        $this->storedExerciseService = $storedExerciseService;
    }

    /**
     * Set answerRepository
     *
     * @param AnswerRepository $answerRepository
     */
    public function setAnswerRepository($answerRepository)
    {
        $this->answerRepository = $answerRepository;
    }

    /**
     * Set itemRepository
     *
     * @param ItemRepository $itemRepository
     */
    public function setItemRepository(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    /**
     * Set exerciseService
     *
     * @param ExerciseServiceInterface $exerciseService
     */
    public function setExerciseService(ExerciseServiceInterface $exerciseService)
    {
        $this->exerciseService = $exerciseService;
    }

    /**
     * Set attemptService
     *
     * @param AttemptServiceInterface $attemptService
     */
    public function setAttemptService($attemptService)
    {
        $this->attemptService = $attemptService;
    }

    /**
     * Find an item by its id
     *
     * @param int $itemId
     *
     * @return Item
     */
    public function get($itemId)
    {
        return $this->itemRepository->find($itemId);
    }

    /**
     * Find the item and the correction (if corrected by this user)
     *
     * @param int     $itemId
     * @param int     $attemptId
     *
     * @return ItemResource
     */
    public function findItemAndCorrectionByAttempt(
        $itemId,
        $attemptId
    )
    {
        $item = $this->getByAttempt($itemId, $attemptId);

        $answer = $this->answerRepository->findOneBy(
            array(
                'item'    => $item->getId(),
                'attempt' => $attemptId
            )
        );

        // If no correction to do (no user's answer found), return the item
        if (is_null($answer)) {
            $itemResource = ItemResourceFactory::create($item);
            $itemResource->setCorrected(false);
            return $itemResource;
        }

        /** @var Answer $answer */
        // set corrected to true (it is returned)

        // correct it with the exercise service
        return $this->exerciseService->correctItem($answer);
    }

    /**
     * Get an item by attempt
     *
     * @param int $itemId
     * @param int $attemptId
     *
     * @return Item
     */
    public function getByAttempt($itemId, $attemptId)
    {
        $attempt = $this->attemptService->get($attemptId);

        return $this->itemRepository->getByAttempt($itemId, $attempt);
    }

    /**
     * Get all items
     *
     * @param int $exerciseId  Exercise id
     *
     * @return array
     */
    public function getAll($exerciseId = null)
    {
        $storedExercise = null;
        if (!is_null($exerciseId)) {
            $storedExercise = $this->storedExerciseService->get($exerciseId);
        }

        return $this->itemRepository->findAllBy($storedExercise);
    }

    /**
     * Get all items by attempt Id
     *
     * @param CollectionInformation $collectionInformation
     * @param int                   $attemptId
     *
     * @return array
     */
    public function getAllByAttempt($collectionInformation = null, $attemptId = null)
    {
        $attempt = null;
        if (!is_null($attemptId)) {
            $attempt = $this->attemptService->get($attemptId);
        }

        return $this->itemRepository->findAllByAttempt($attempt, $collectionInformation);
    }

    /**
     * Find an item and its correction
     *
     * @param $itemId
     * @param $answerId
     *
     * @return ItemResource
     * @throws NonExistingObjectException
     */
    public function findItemAndCorrectionById($itemId, $answerId)
    {
        /** @var Answer $storedAnswer */
        $storedAnswer = $this->answerRepository->findOneBy(
            array(
                'item' => $itemId,
                'id'   => $answerId
            )
        );

        if (is_null($storedAnswer)) {
            throw new NonExistingObjectException();
        }

        // correct it with the exercise service
        return $this->exerciseService->correctItem($storedAnswer);
    }
}
