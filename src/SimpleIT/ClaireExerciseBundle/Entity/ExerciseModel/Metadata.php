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
     * @var OwnerExerciseModel
     */
    private $ownerExerciseModel;

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $value;

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
     * Set key
     *
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
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
     * Set value
     *
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
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
}
