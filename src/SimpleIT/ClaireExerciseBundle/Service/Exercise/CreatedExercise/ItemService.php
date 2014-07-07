<?php

namespace SimpleIT\ClaireExerciseBundle\Service\Exercise\CreatedExercise;

use SimpleIT\ClaireExerciseBundle\Entity\CreatedExercise\Answer;
use SimpleIT\ClaireExerciseBundle\Entity\CreatedExercise\Item;
use SimpleIT\ClaireExerciseBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ItemResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ItemResourceFactory;
use SimpleIT\ClaireExerciseBundle\Repository\Exercise\CreatedExercise\AnswerRepository;
use SimpleIT\ClaireExerciseBundle\Repository\Exercise\CreatedExercise\ItemRepository;
use SimpleIT\ClaireExerciseBundle\Service\Exercise\ExerciseCreation\ExerciseServiceInterface;
use SimpleIT\ClaireExerciseBundle\Service\TransactionalService;
use SimpleIT\ClaireExerciseBundle\Model\Collection\CollectionInformation;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
     * @param int $itemId
     * @param int $attemptId
     * @param int $userId
     *
     * @return ItemResource
     */
    public function findItemAndCorrectionByAttempt(
        $itemId,
        $attemptId,
        $userId = null
    )
    {
        $item = $this->getByAttempt($itemId, $attemptId, $userId);

        $answer = $this->answerRepository->findOneBy(
            array(
                'item'    => $item->getId(),
                'attempt' => $attemptId
            )
        );

        // If no correction to do (no user's answer found), return the item with no solution
        if (is_null($answer)) {
            $itemResource = ItemResourceFactory::create($item);
            $itemResource->setCorrected(false);

            return $this->exerciseService->noSolutionItem($itemResource);
        }

        /** @var Answer $answer */

        // correct it with the exercise service
        return $this->exerciseService->correctItem($answer);
    }

    /**
     * Get an item by attempt
     *
     * @param int $itemId
     * @param int $attemptId
     * @param int $userId
     *
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @return Item
     */
    public function getByAttempt($itemId, $attemptId, $userId = null)
    {
        $attempt = $this->attemptService->get($attemptId, $userId);

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
     * @param int $attemptId
     * @param int $userId
     *
     * @return array
     */
    public function getAllByAttempt(
        $attemptId,
        $userId
    )
    {
        $attempt = $this->attemptService->get($attemptId, $userId);

        $items = array();
        /** @var Item $item */
        foreach ($attempt->getExercise()->getItems() as $item) {
            $items[] = $this->findItemAndCorrectionByAttempt($item->getId(), $attemptId, $userId);
        }

        return $items;
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
