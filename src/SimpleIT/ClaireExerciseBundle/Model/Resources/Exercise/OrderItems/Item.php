<?php

namespace SimpleIT\ClaireExerciseBundle\Model\Resources\Exercise\OrderItems;

use JMS\Serializer\Annotation as Serializer;
use SimpleIT\ClaireExerciseBundle\Model\Resources\Exercise\Common\CommonItem;
use SimpleIT\ClaireExerciseBundle\Model\Resources\Exercise\Common\Markable;

/**
 * Class Exercise
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class Item extends CommonItem implements Markable
{
    /**
     * @var array $objects An array of ExerciseObject
     * @Serializer\Type("array<SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseObject\ExerciseObject>")
     * @Serializer\Groups({"details", "corrected", "not_corrected", "item_storage"})
     */
    private $objects = array();

    /**
     * @var boolean $giveFirst True if the first element must be given to the
     *                         learner
     * @Serializer\Type("boolean")
     * @Serializer\Groups({"details", "corrected", "not_corrected", "item_storage"})
     */
    private $giveFirst;

    /**
     * @var boolean $giveLast True if the last element must be given to the
     *                         learner
     * @Serializer\Type("boolean")
     * @Serializer\Groups({"details", "corrected", "not_corrected", "item_storage"})
     */
    private $giveLast;

    /**
     * The possible variants of the sequence. It is a recursive array of the
     * following form, for instance:
     * ['name'] => 'or',
     * [0] => 1,
     * [1] => array (
     *      ['name'] => 'di',
     *      [0] => 3,
     *      [1] => array (
     *              ['name'] => 'or',
     *              [0] => 2,
     *              [1] => 0,
     *              [2] => 5
     *          ),
     *      [2] => 6
     *  ),
     * [2] => 4
     * )
     * 'or' indicates a block in which the order of the elements cannot be
     * moved. 'di' indicates a block in which the order can be moved.
     * Every index can contain another an array (order or disorder block) or
     * a value that is the index of the ExerciseObject in the $objects array.
     *
     * @var array $solution
     * @Serializer\Type("array")
     * @Serializer\Groups({"details", "corrected", "not_corrected", "item_storage"})
     */
    private $solutions = array();

    /**
     * The learner's answers.
     *
     * @var array
     * @Serializer\Type("array")
     * @Serializer\Groups({"details", "corrected"})
     */
    private $answers = array();

    /**
     * @var float
     * @Serializer\Type("float")
     * @Serializer\Groups({"details", "corrected"})
     */
    private $mark = null;

    /**
     * Check if the Markable has a mark
     *
     * @return boolean
     */
    public function isMarked()
    {
        return !is_null($this->mark);
    }

    /**
     * Get mark
     *
     * @return float
     */
    public function getMark()
    {
        return $this->mark;
    }

    /**
     * Set mark
     *
     * @param float $mark
     */
    public function setMark($mark)
    {
        $this->mark = $mark;
    }

    /**
     * Get solution
     *
     * @return array
     */
    public function getSolutions()
    {
        return $this->solutions;
    }

    /**
     * Get objects
     *
     * @return array An array of ExerciseObject
     */
    public function getObjects()
    {
        return $this->objects;
    }

    /**
     * Shuffle objects and update the solution
     */
    public function shuffleObjects()
    {
        // create an array with the keys
        $objKeys = array_keys($this->objects);

        // shuffle the array of keys
        if ($this->giveFirst && $this->giveLast) {
            $first = $objKeys[0];
            $last = $objKeys[count($objKeys) - 1];
            unset ($objKeys[count($objKeys) - 1]);
            unset ($objKeys[0]);
            shuffle($objKeys);
            $objKeys = array_merge(array($first), $objKeys, array($last));
        } elseif (!$this->giveFirst && $this->giveLast) {
            $last = $objKeys[count($objKeys) - 1];
            unset ($objKeys[count($objKeys) - 1]);
            shuffle($objKeys);
            $objKeys = array_merge($objKeys, array($last));
        } elseif ($this->giveFirst && !$this->giveLast) {
            $first = $objKeys[0];
            unset ($objKeys[0]);
            shuffle($objKeys);
            $objKeys = array_merge(array($first), $objKeys);
        } else {
            shuffle($objKeys);
        }

        // create new arrays
        $newObjects = array();

        // fill the new object array with the shuffled keys
        foreach ($objKeys as $index => $key) {
            $newObjects[$index] = $this->objects[$key];
        }

        // modify the solution
        $this->modifySolution($this->solutions, $objKeys);

        // Copy in the exercise object arrays
        $this->objects = $newObjects;
    }

    /**
     * Modify the solution according to a new array of keys
     *
     * @param array $solutions
     * @param array $objKeys
     */
    private function modifySolution(array &$solutions, array $objKeys)
    {
        foreach ($solutions as $key => &$sub) {
            if (is_array($sub)) {
                $this->modifySolution($sub, $objKeys);
            } elseif ($key !== "name") {
                $sub = array_search($sub, $objKeys);
            }
        }
    }

    /**
     * Set answers
     *
     * @param array $answers
     */
    public function setAnswers($answers)
    {
        $this->answers = $answers;
    }

    /**
     * Get answers
     *
     * @return array
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * Set giveFirst
     *
     * @param boolean $giveFirst
     */
    public function setGiveFirst($giveFirst)
    {
        $this->giveFirst = $giveFirst;
    }

    /**
     * Get giveFirst
     *
     * @return boolean
     */
    public function getGiveFirst()
    {
        return $this->giveFirst;
    }

    /**
     * Set giveLast
     *
     * @param boolean $giveLast
     */
    public function setGiveLast($giveLast)
    {
        $this->giveLast = $giveLast;
    }

    /**
     * Get giveLast
     *
     * @return boolean
     */
    public function getGiveLast()
    {
        return $this->giveLast;
    }

    /**
     * Set objects
     *
     * @param array $objects
     */
    public function setObjects($objects)
    {
        $this->objects = $objects;
    }

    /**
     * Set solutions
     *
     * @param array $solutions
     */
    public function setSolutions($solutions)
    {
        $this->solutions = $solutions;
    }
}
