<?php

namespace SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseModel\GroupItems;

use JMS\Serializer\Annotation as Serializer;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseModel\Common\CommonModel;

/**
 * A Group Items exercises model. It containts one or more blocks of objects.
 * It also describes the group names and how to group the objects. An option
 * allows to choose if the names of the groups must be shown, hidden or asked.
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class Model extends CommonModel
{
    /**
     * @const SHOW = "show"
     */
    const SHOW = "show";

    /**
     * @const HIDE = "hide"
     */
    const HIDE = "hide";

    /**
     * @const ASK = "ask"
     */
    const ASK = "ask";

    /**
     * @var array $objectBlocks An array of ObjectBlock
     * @Serializer\Type("array<SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseModel\GroupItems\ObjectBlock>")
     * @Serializer\Groups({"details", "exercise_model_storage"})
     */
    private $objectBlocks = array();

    /**
     * @var string $displayGroupNames show, hide, ask
     * @Serializer\Type("string")
     * @Serializer\Groups({"details", "exercise_model_storage"})
     */
    private $displayGroupNames;

    /**
     * @var ClassificationConstraints $classifConstr
     * @Serializer\Type("SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseModel\GroupItems\ClassificationConstraints")
     * @Serializer\Groups({"details", "exercise_model_storage"})
     */
    private $classifConstr = null;

    /**
     * Get the object blocks
     *
     * @return array An aray of QuestionBlock
     */
    public function getObjectBlocks()
    {
        return $this->objectBlocks;
    }

    /**
     * Set object blocks
     *
     * @param array $objectBlocks An aray of QuestionBlock
     */
    public function setObjectBlocks($objectBlocks)
    {
        $this->objectBlocks = $objectBlocks;
    }

    /**
     * Add an object block
     *
     * @param ObjectBlock $objectBlock
     */
    public function addObjectBlock(ObjectBlock $objectBlock)
    {
        $this->objectBlocks[] = $objectBlock;
    }

    /**
     * Get DisplayGroupNames
     *
     * @return int displayGroupNames
     */
    public function getDisplayGroupNames()
    {
        return $this->displayGroupNames;
    }

    /**
     * Set displayGroupNames
     *
     * @param int $displayGroupNames
     */
    public function setDisplayGroupNames($displayGroupNames)
    {
        $this->displayGroupNames = $displayGroupNames;
    }

    /**
     * Get global classification constraints
     *
     * @return ClassificationConstraints
     */
    public function getClassifConstr()
    {
        return $this->classifConstr;
    }

    /**
     * Set global classification constraints
     *
     * @param ClassificationConstraints $classifConstr
     */
    public function setClassifConstr($classifConstr)
    {
        $this->classifConstr = $classifConstr;
    }
}
