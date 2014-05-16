<?php

namespace SimpleIT\ClaireExerciseBundle\Entity\Test;

use SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\ExerciseModel;

/**
 * Class TestModelPosition
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class TestModelPosition
{
    /**
     * @var ExerciseModel
     */
    private $exerciseModel;

    /**
     * @var TestModel
     */
    private $testModel;

    /**
     * @var int
     */
    private $position;

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
