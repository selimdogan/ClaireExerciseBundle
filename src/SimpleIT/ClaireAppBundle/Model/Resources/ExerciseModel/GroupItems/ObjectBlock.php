<?php

namespace SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\GroupItems;

use JMS\Serializer\Annotation as Serializer;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\Common\ResourceBlock;

/**

/**
 * Class ObjectBlock
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ObjectBlock extends ResourceBlock
{
    /**
     * @var string $metaToDisplay Indicates the metadata field which content must be displayed
     * instead of the objects
     * @Serializer\Type("string")
     * @Serializer\Groups({"details", "exercise_model_storage"})
     */
    private $metaToDisplay = null;

    /**
     * @var ClassificationConstraints $classifConstr
     * @Serializer\Type("SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\GroupItems\ClassificationConstraints")
     * @Serializer\Groups({"details", "exercise_model_storage"})
     */
    private $classifConstr = null;

    /**
     * Constructor
     *
     * @param int $numberOfOccurrences
     */
    function __construct($numberOfOccurrences)
    {
        $this->numberOfOccurrences = $numberOfOccurrences;
    }

    /**
     * Get ClassificationConstraints
     *
     * @return ClassificationConstraints
     */
    public function getClassifConstr()
    {
        return $this->classifConstr;
    }

    /**
     * Set ClassificationConstraints
     *
     * @param ClassificationConstraints $classifConstr
     */
    public function setClassifConstr($classifConstr)
    {
        $this->classifConstr = $classifConstr;
    }

    /**
     * Get Metadata to display
     *
     * @return string
     */
    public function getMetaToDisplay()
    {
        return $this->metaToDisplay;
    }

    /**
     * Set Metadata to display
     *
     * @param string $metaToDisplay
     */
    public function setMetaToDisplay($metaToDisplay)
    {
        $this->metaToDisplay = $metaToDisplay;
    }
}
