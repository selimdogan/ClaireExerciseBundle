<?php

namespace SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\OrderItems;

use JMS\Serializer\Annotation as Serializer;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\Common\ResourceBlock;

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
     * @var string $metaKey The key of the metadata field used to determine the order of the
     * sequence
     * @Serializer\Type("string")
     * @Serializer\Groups({"details", "exercise_model_storage"})
     */
    private $metaKey;

    /**
     * Constructor
     *
     * @param int    $numberOfOccurrences
     * @param string $metaKey
     */
    function __construct($numberOfOccurrences, $metaKey)
    {
        $this->numberOfOccurrences = $numberOfOccurrences;
        $this->metaKey = $metaKey;
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

    /**
     * Get metaKey
     *
     * @return string
     */
    public function getMetaKey()
    {
        return $this->metaKey;
    }

    /**
     * Set metaKey
     *
     * @param string $metaKey
     */
    public function setMetaKey($metaKey)
    {
        $this->metaKey = $metaKey;
    }
}
