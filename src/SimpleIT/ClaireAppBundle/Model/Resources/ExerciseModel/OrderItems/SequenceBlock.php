<?php

namespace SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\OrderItems;

use JMS\Serializer\Annotation as Serializer;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\Common\ResourceBlock;

/**

/**
 * Class SequenceBlock
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class SequenceBlock extends ResourceBlock
{
    /**
     * @var boolean $keepAll Section method. True if all the segments must be kept. Several are
     * grouped to form a bigger one. False if only extracts are taken from the sequence. Some
     * parts may not appear in the exercise.
     * @Serializer\Type("boolean")
     * @Serializer\Groups({"details", "exercise_model_storage"})
     */
    private $keepAll;

    /**
     * @var int $numberOfParts Number of parts in the exercise sequence. Null if no constraint:
     * all the segments are used.
     * @Serializer\Type("integer")
     * @Serializer\Groups({"details", "exercise_model_storage"})
     */
    private $numberOfParts = null;

    /**
     * @var boolean $useFirst True if the begining of the sequence must be used. Only makes sense
     * when $keepAll = false.
     * @Serializer\Type("boolean")
     * @Serializer\Groups({"details", "exercise_model_storage"})
     */
    private $useFirst;

    /**
     * @var boolean $useLast True if the end of the sequence must be used. Only makes sense when
     * $keepAll = false.
     * @Serializer\Type("boolean")
     * @Serializer\Groups({"details", "exercise_model_storage"})
     */
    private $useLast;

    /**
     * Constructor
     */
    function __construct()
    {
        $this->numberOfOccurrences = 1;
    }

    /**
     * Get keepAll
     *
     * @return boolean
     */
    public function isKeepAll()
    {
        return $this->keepAll;
    }

    /**
     * Set keepAll
     *
     * @param boolean $keepAll
     */
    public function setKeepAll($keepAll)
    {
        $this->keepAll = $keepAll;
    }

    /**
     * Get numberOfParts
     *
     * @return int
     */
    public function getNumberOfParts()
    {
        return $this->numberOfParts;
    }

    /**
     * Set numberOfParts
     *
     * @param int $numberOfParts
     */
    public function setNumberOfParts($numberOfParts)
    {
        $this->numberOfParts = $numberOfParts;
    }

    /**
     * Get useFirst
     *
     * @return boolean
     */
    public function isUseFirst()
    {
        return $this->useFirst;
    }

    /**
     * Set useFirst
     *
     * @param boolean $useFirst
     */
    public function setUseFirst($useFirst)
    {
        $this->useFirst = $useFirst;
    }

    /**
     * Get useLast
     *
     * @return boolean
     */
    public function isUseLast()
    {
        return $this->useLast;
    }

    /**
     * Set useLast
     *
     * @param boolean $useLast
     */
    public function setUseLast($useLast)
    {
        $this->useLast = $useLast;
    }
}
