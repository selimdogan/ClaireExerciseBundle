<?php

namespace SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseModel\OrderItems;

use JMS\Serializer\Annotation as Serializer;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseModel\Common\CommonModel;

/**
 * Model of a OrderItems exercise.
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class Model extends CommonModel
{
    /**
     * @const ASCENDENT = "asc"
     */
    const ASCENDENT = "asc";

    /**
     * @const DESCENDENT = "desc"
     */
    const DESCENDENT = "desc";

    /**
     * @var SequenceBlock $sequenceBlock
     * @Serializer\Type("SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseModel\OrderItems\SequenceBlock")
     * @Serializer\Groups({"details", "exercise_model_storage"})
     */
    private $sequenceBlock = null;

    /**
     * @var array $objectBlocks An array of ObjectBlock
     * @Serializer\Type("array<SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseModel\OrderItems\ObjectBlock>")
     * @Serializer\Groups({"details", "exercise_model_storage"})
     */
    private $objectBlocks = array();

    /**
     * @var boolean $giveFirst Precise if the first element must be revealed in the exercise
     * @Serializer\Type("boolean")
     * @Serializer\Groups({"details", "exercise_model_storage"})
     */
    private $giveFirst = null;

    /**
     * @var boolean $giveLast Precise if the last element must be revealed in the exercise
     * @Serializer\Type("boolean")
     * @Serializer\Groups({"details", "exercise_model_storage"})
     */
    private $giveLast = null;

    /**
     * @var string $order The order (asc or desc) in the case of an object list
     * @Serializer\Type("string")
     * @Serializer\Groups({"details", "exercise_model_storage"})
     */
    private $order = null;

    /**
     * @var boolean $showValues Show the values in the case of an object list
     * @Serializer\Type("boolean")
     * @Serializer\Groups({"details", "exercise_model_storage"})
     */
    private $showValues = null;

    /**
     * Indicates if the exercise content is an object list.
     *
     * @return boolean True if the model contains an object list as sequence
     */
    public function isObjectList()
    {
        return !empty($this->objectBlocks);
    }

    /**
     * Indicates if the exercise content is a sequence object.
     *
     * @return boolean True if the model contains a sequence object
     */
    public function isSequenceObject()
    {
        return !is_null($this->sequenceBlock);
    }

    /**
     * Get sequenceBlock
     *
     * @return SequenceBlock
     */
    public function getSequenceBlock()
    {
        return $this->sequenceBlock;
    }

    /**
     * Set sequenceBlock
     *
     * @param SequenceBlock $sequenceBlock
     *
     * @throws \Exception
     */
    public function setSequenceBlock($sequenceBlock)
    {
        $this->sequenceBlock = $sequenceBlock;
        if (!empty($this->objectBlocks)) {
            throw new \LogicException("Both sequenceBlock and objectBlock cannot be filled");
        }
    }

    /**
     * Get objectBlocks
     *
     * @return array An array of ObjectBlock
     */
    public function getObjectBlocks()
    {
        return $this->objectBlocks;
    }

    /**
     * Set objectBlocks
     *
     * @param array $objectBlocks An array of ObjectBlock
     *
     * @throws \Exception
     */
    public function setObjectBlocks($objectBlocks)
    {
        $this->objectBlocks = $objectBlocks;
        if (!is_null($this->sequenceBlock)) {
            throw new \LogicException("Both sequenceBlock and objectBlock cannot be filled");
        }
    }

    /**
     * Add an objectBlock
     *
     * @param ObjectBlock $objectBlock
     *
     * @throws \Exception
     */
    public function addObjectBlock(ObjectBlock $objectBlock)
    {
        $this->objectBlocks[] = $objectBlock;
        if (!is_null($this->sequenceBlock)) {
            throw new \LogicException("Both sequenceBlock and objectBlock cannot be filled");
        }
    }

    /**
     * Get giveFirst
     *
     * @return boolean
     */
    public function isGiveFirst()
    {
        return $this->giveFirst;
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
     * Get giveLast
     *
     * @return boolean
     */
    public function isGiveLast()
    {
        return $this->giveLast;
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
     * Get order
     *
     * @return string
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set order
     *
     * @param string $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * Set showValues
     *
     * @param boolean $showValues
     */
    public function setShowValues($showValues)
    {
        $this->showValues = $showValues;
    }

    /**
     * Get showValues
     *
     * @return boolean
     */
    public function getShowValues()
    {
        return $this->showValues;
    }
}
