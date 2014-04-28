<?php

namespace SimpleIT\ExerciseBundle\Entity\Test;

use SimpleIT\ExerciseBundle\Entity\CreatedExercise\StoredExercise;

/**
 * Class TestPosition
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class TestPosition
{
    /**
     * @var StoredExercise
     */
    private $exercise;

    /**
     * @var Test
     */
    private $test;

    /**
     * @var int
     */
    private $position;

    /**
     * Set exercise
     *
     * @param StoredExercise $exercise
     */
    public function setExercise($exercise)
    {
        $this->exercise = $exercise;
    }

    /**
     * Get exercise
     *
     * @return StoredExercise
     */
    public function getExercise()
    {
        return $this->exercise;
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
     * Set test
     *
     * @param Test $test
     */
    public function setTest($test)
    {
        $this->test = $test;
    }

    /**
     * Get test
     *
     * @return Test
     */
    public function getTest()
    {
        return $this->test;
    }
}
