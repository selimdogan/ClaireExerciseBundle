<?php

namespace SimpleIT\ExerciseBundle\Entity;

use SimpleIT\ExerciseBundle\Entity\CreatedExercise\StoredExercise;
use SimpleIT\ExerciseBundle\Entity\Test\TestPosition;

/**
 * Class to manage the creation of TestPosition
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class TestPositionFactory
{
    /**
     * Create a new TestPosition
     *
     * @param StoredExercise $exercise
     * @param int            $position
     *
     * @return TestPosition
     */
    public static function create(StoredExercise $exercise, $position)
    {
        $testPosition = new TestPosition();
        $testPosition->setExercise($exercise);
        $testPosition->setPosition($position);

        return $testPosition;
    }
}
