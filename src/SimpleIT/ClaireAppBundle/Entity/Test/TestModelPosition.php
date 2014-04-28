<?php

namespace SimpleIT\ExerciseBundle\Entity\Test;

use SimpleIT\ExerciseBundle\Entity\ExerciseModel\OwnerExerciseModel;

/**
 * Class TestModelPosition
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class TestModelPosition
{
    /**
     * @var OwnerExerciseModel
     */
    private $ownerExerciseModel;

    /**
     * @var TestModel
     */
    private $testModel;

    /**
     * @var int
     */
    private $position;

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

    /**
     * Set position
     *
     * @param int $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * Get position
     *
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set testModel
     *
     * @param TestModel $testModel
     */
    public function setTestModel($testModel)
    {
        $this->testModel = $testModel;
    }

    /**
     * Get testModel
     *
     * @return TestModel
     */
    public function getTestModel()
    {
        return $this->testModel;
    }
}
