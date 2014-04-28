<?php

namespace SimpleIT\ExerciseBundle\Entity\CreatedExercise;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use SimpleIT\ExerciseBundle\Entity\ExerciseModel\OwnerExerciseModel;

/**
 * Claire stored exercise entity
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class StoredExercise
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $content;

    /**
     * @var OwnerExerciseModel
     */
    private $ownerExerciseModel;

    /**
     * @var Collection $items
     */
    private $items;

    /**
     * @var Collection $attempts
     */
    private $attempts;

    /**
     * @var Collection $items
     */
    private $testPositions;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set content
     *
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Set ownerExerciseModel
     *
     * @param OwnerExerciseModel $ownerExerciseModel
     */
    public function setOwnerExerciseModel($ownerExerciseModel)
    {
        $this->ownerExerciseModel = $ownerExerciseModel;
    }

    /**
     * Get ownerExerciseModel
     *
     * @return OwnerExerciseModel
     */
    public function getOwnerExerciseModel()
    {
        return $this->ownerExerciseModel;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get items
     *
     * @return Collection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Set items
     *
     * @param Collection $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    /**
     * Add an item
     *
     * @param Item $item Item
     */
    public function addItem(Item $item)
    {
        $this->items->add($item);
    }

    /**
     * Remove an item
     *
     * @param Item $item Item
     */
    public function removeItem(Item $item)
    {
        $this->items->removeElement($item);
    }

    /**
     * Set testPositions
     *
     * @param Collection $testPositions
     */
    public function setTestPositions($testPositions)
    {
        $this->testPositions = $testPositions;
    }

    /**
     * Get testPositions
     *
     * @return Collection
     */
    public function getTestPositions()
    {
        return $this->testPositions;
    }

    /**
     * Set attempts
     *
     * @param Collection $attempts
     */
    public function setAttempts($attempts)
    {
        $this->attempts = $attempts;
    }

    /**
     * Get attempts
     *
     * @return Collection
     */
    public function getAttempts()
    {
        return $this->attempts;
    }
}
