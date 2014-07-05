<?php

namespace SimpleIT\ClaireExerciseBundle\Service\Exercise\CreatedExercise;

use SimpleIT\ClaireExerciseBundle\Entity\CreatedExercise\Item;
use SimpleIT\ClaireExerciseBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ItemResource;

/**
 * Interface for service which manages the stored exercises
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
interface ItemServiceInterface
{
    /**
     * Find an item by its id
     *
     * @param int $itemId
     *
     * @return Item
     */
    public function get($itemId);

    /**
     * Find the item and the correction (if corrected by this user)
     *
     * @param int $itemId
     * @param int $attemptId
     *
     * @return ItemResource
     */
    public function findItemAndCorrectionByAttempt(
        $itemId,
        $attemptId
    );

    /**
     * Get an item by attempt
     *
     * @param int $itemId
     * @param int $attemptId
     *
     * @return Item
     */
    public function getByAttempt($itemId, $attemptId);

    /**
     * Get all items
     *
     * @param int $exerciseId  Exercise id
     *
     * @return array
     */
    public function getAll($exerciseId = null);

    /**
     * Get all items by attempt Id
     *
     * @param int                   $attemptId
     * @param int                   $userId
     *
     * @return array
     */
    public function getAllByAttempt(
        $attemptId,
        $userId
    );

    /**
     * Find an item and its correction
     *
     * @param $itemId
     * @param $answerId
     *
     * @return ItemResource
     * @throws NonExistingObjectException
     */
    public function findItemAndCorrectionById($itemId, $answerId);
}
