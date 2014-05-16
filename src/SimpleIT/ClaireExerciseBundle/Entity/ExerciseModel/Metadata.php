<?php

namespace SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel;

/**
 * Exercise Model Metadata entity
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class Metadata
{
    /**
     * @var ExerciseModel
     */
    private $exerciseModel;

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $value;

    /**
     * Set key
     *
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * Get key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set value
     *
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
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
}
