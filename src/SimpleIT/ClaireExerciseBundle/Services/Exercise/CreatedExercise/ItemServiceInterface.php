<?php

namespace SimpleIT\ClaireExerciseBundle\Service\CreatedExercise;

use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Entity\CreatedExercise\Item;
use SimpleIT\Utils\Collection\PaginatorInterface;

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
     * @param int     $itemId
     * @param int     $attemptId
     * @param boolean $corrected
     *
     * @return Item
     */
    public function findItemAndCorrectionByAttempt(
        $itemId,
        $attemptId,
        &$corrected
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
     * @return PaginatorInterface
     */
    public function getAll($exerciseId = null);

    /**
     * Get all items by attempt Id
     *
     * @param int $attemptId
     *
     * @return PaginatorInterface
     */
    public function getAllByAttempt($attemptId = null);

    /**
     * Find an item and its correction
     *
     * @param $itemId
     * @param $answerId
     *
     * @return Item
     * @throws NonExistingObjectException
     */
    public function findItemAndCorrectionById($itemId, $answerId);
}
