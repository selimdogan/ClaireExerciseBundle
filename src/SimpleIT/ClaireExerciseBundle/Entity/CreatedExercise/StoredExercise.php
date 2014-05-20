<?php

namespace SimpleIT\ClaireExerciseBundle\Entity\CreatedExercise;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\ExerciseModel;

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
     * @var ExerciseModel
     */
    private $exerciseModel;

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
        $this->attempts = new ArrayCollection();
    }

    /**
     * Set attempts
     *
     * @param \Doctrine\Common\Collections\Collection $attempts
     */
    public function setAttempts($attempts)
    {
        $this->attempts = $attempts;
    }

    /**
     * Get attempts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAttempts()
    {
        return $this->attempts;
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
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set exerciseModel
     *
     * @param \SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\ExerciseModel $exerciseModel
     */
    public function setExerciseModel($exerciseModel)
    {
        $this->exerciseModel = $exerciseModel;
    }

    /**
     * Get exerciseModel
     *
     * @return \SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\ExerciseModel
     */
    public function getExerciseModel()
    {
        return $this->exerciseModel;
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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set items
     *
     * @param \Doctrine\Common\Collections\Collection $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    /**
     * Add item
     *
     * @param Item $item
     */
    public function addItem(Item $item)
    {
        $this->items[] = $item;
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Set testPositions
     *
     * @param \Doctrine\Common\Collections\Collection $testPositions
     */
    public function setTestPositions($testPositions)
    {
        $this->testPositions = $testPositions;
    }

    /**
     * Get testPositions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTestPositions()
    {
        return $this->testPositions;
    }


}
