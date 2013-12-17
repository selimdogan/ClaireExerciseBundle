<?php

namespace SimpleIT\ClaireAppBundle\Services\Exercise;

use SimpleIT\ApiResourcesBundle\Exercise\ItemResource;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatedCollection;

/**
 * Interface for ItemService class
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
interface ItemServiceInterface
{
    /**
     * Get an item by its id
     *
     * @param int  $itemId Item Id
     * @param bool $showCorrection
     *
     * @return ItemResource
     */
    public function get($itemId, $showCorrection = false);

    /**
     * Get an item object from the id of the attempt and the id of the item
     *
     * @param int     $attemptId
     * @param int     $itemId
     * @param boolean $corrected
     *
     * @return object The item object
     */
    public function getItemObjectFromAttempt($attemptId, $itemId, &$corrected);

    /**
     * Get an item (corrected if it is answered) by its attempt and id
     *
     * @param $attemptId
     * @param $itemId
     *
     * @return ItemResource
     */
    public function getByAttempt($attemptId, $itemId);

    /**
     * Get all the items of an exercise
     *
     * @param int $exerciseId
     *
     * @return PaginatedCollection
     */
    public function getAllFromExercise($exerciseId);

    /**
     * Get all the items for this attempt
     *
     * @param int                   $attemptId
     * @param CollectionInformation $collectionInformation
     *
     * @return PaginatedCollection
     */
    public function getAll($attemptId, $collectionInformation = null);

    /**
     * Get the id of the first item of an attempt
     *
     * @param $attemptId
     *
     * @return mixed
     */
    public function getFirstItemId($attemptId);
}
