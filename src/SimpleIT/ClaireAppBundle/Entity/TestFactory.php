<?php

namespace SimpleIT\ExerciseBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use SimpleIT\ExerciseBundle\Entity\Test\Test;
use SimpleIT\ExerciseBundle\Entity\Test\TestModel;
use SimpleIT\ExerciseBundle\Entity\Test\TestPosition;

/**
 * Class to manage the creation of Test
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class TestFactory
{
    /**
     * Create a new Test
     *
     * @param array     $positions
     * @param TestModel $testModel
     *
     * @return Test
     */
    public static function create(array $positions, TestModel $testModel)
    {
        $test = new Test();

        foreach ($positions as &$pos) {
            /** @var TestPosition $pos */
            $pos->setTest($test);
        }

        $test->setTestPositions(new ArrayCollection($positions));
        $test->setTestModel($testModel);

        return $test;
    }
}
