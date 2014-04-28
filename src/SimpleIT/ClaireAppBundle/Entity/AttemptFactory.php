<?php

namespace SimpleIT\ExerciseBundle\Entity;

use SimpleIT\CommonBundle\Entity\User;
use SimpleIT\ExerciseBundle\Entity\CreatedExercise\Attempt;
use SimpleIT\ExerciseBundle\Entity\CreatedExercise\StoredExercise;
use SimpleIT\ExerciseBundle\Entity\Test\TestAttempt;

/**
 * Class to manage the creation of Attempts
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class AttemptFactory
{
    /**
     * Create a new Attempt
     *
     * @param StoredExercise $exercise
     * @param User           $user
     * @param TestAttempt    $testAttempt
     * @param int            $position
     *
     * @return TestAttempt
     */
    public static function create(
        StoredExercise $exercise,
        User $user,
        $testAttempt,
        $position = null
    )
    {
        $attempt = new Attempt();
        $attempt->setExercise($exercise);
        $attempt->setUser($user);

        $attempt->setPosition($position);

        if (is_null($testAttempt)) {
            $attempt->setTestAttempt(null);
        } else {
            $attempt->setTestAttempt($testAttempt);
        }

        return $attempt;
    }
}
