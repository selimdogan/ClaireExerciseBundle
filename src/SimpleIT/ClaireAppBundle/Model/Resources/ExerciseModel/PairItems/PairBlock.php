<?php

namespace SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\PairItems;

use JMS\Serializer\Annotation as Serializer;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\Common\ResourceBlock;

/**
 * Class PairBlock
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class PairBlock extends ResourceBlock
{
    /**
     * The content of the block is objects that are pairs as themselves
     *
     * @const PAIR_OBJECT = 1
     */
    const PAIR_OBJECT = "pair-object";

    /**
     * The content of the block is objects which should be linked with the text
     * value found in the metadata specified in $pairMetaKey
     *
     * @const META_VALUE = 2
     */
    const META_VALUE = "text-meta-value";

    /**
     * The content of the block is objects which should be linked with another
     * resource which is designated by the metadata specified in $pairMetaKey
     *
     * @const META_RESOURCE = 3
     */
    const META_RESOURCE = "resource-meta-value";

    /**
     * @var string $pairMetaKey The key of the metadata on which the pair is built
     * @Serializer\Type("string")
     * @Serializer\Groups({"details", "exercise_model_storage"})
     */
    private $pairMetaKey = null;

    /**
     * @var string $fixMetaToDisplay Indicates the metadata field which content must be
     * displayed instead of the fix objects of the pair
     * @Serializer\Type("string")
     * @Serializer\Groups({"details", "exercise_model_storage"})
     */
    private $fixMetaToDisplay = null;

    /**
     * @var string $mobileMetaToDisplay Indicates the metadata field which content must be
     * displayed instead of the mobile objects of the pair
     * @Serializer\Type("string")
     * @Serializer\Groups({"details", "exercise_model_storage"})
     */
    private $mobileMetaToDisplay = null;

    /**
     * Constructor
     *
     * @param int    $numberOfOccurrences
     * @param string $pairMetaKey
     */
    function __construct($numberOfOccurrences, $pairMetaKey = null)
    {
        $this->numberOfOccurrences = $numberOfOccurrences;
        $this->pairMetaKey = $pairMetaKey;
    }

    /**
     * Get meta key
     *
     * @return string
     */
    public function getPairMetaKey()
    {
        return $this->pairMetaKey;
    }

    /**
     * Set meta key
     *
     * @param string $pairMetaKey
     */
    public function setPairMetaKey($pairMetaKey)
    {
        $this->pairMetaKey = $pairMetaKey;
    }

    /**
     * Get fix meta to display
     *
     * @return string
     */
    public function getFixMetaToDisplay()
    {
        return $this->fixMetaToDisplay;
    }

    /**
     * Set fix meta to display
     *
     * @param string $fixMetaToDisplay
     */
    public function setFixMetaToDisplay($fixMetaToDisplay)
    {
        $this->fixMetaToDisplay = $fixMetaToDisplay;
    }

    /**
     * Get mobile meta to display
     *
     * @return string
     */
    public function getMobileMetaToDisplay()
    {
        return $this->mobileMetaToDisplay;
    }

    /**
     * Set mobile meta to display
     *
     * @param string $mobileMetaToDisplay
     */
    public function setMobileMetaToDisplay($mobileMetaToDisplay)
    {
        $this->mobileMetaToDisplay = $mobileMetaToDisplay;
    }
}
