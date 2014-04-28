<?php

namespace SimpleIT\ExerciseBundle\Entity;

use SimpleIT\CommonBundle\Entity\User;
use SimpleIT\ExerciseBundle\Entity\Test\Test;
use SimpleIT\ExerciseBundle\Entity\Test\TestAttempt;

/**
 * Class to manage the creation of Test Attempts
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class TestAttemptFactory
{
    /**
     * Create a new TestAttempt
     *
     * @param Test $test
     * @param User $user
     *
     * @return TestAttempt
     */
    public static function create(Test $test, User $user)
    {
        $testAttempt = new TestAttempt();
        $testAttempt->setTest($test);
        $testAttempt->setUser($user);

        return $testAttempt;
    }
}
