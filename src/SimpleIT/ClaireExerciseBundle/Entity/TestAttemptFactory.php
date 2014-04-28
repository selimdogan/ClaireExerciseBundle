<?php

namespace SimpleIT\ClaireExerciseBundle\Entity;

use SimpleIT\ClaireExerciseBundle\Entity\User\User;
use SimpleIT\ClaireExerciseBundle\Entity\Test\Test;
use SimpleIT\ClaireExerciseBundle\Entity\Test\TestAttempt;

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
