<?php

namespace SimpleIT\ClaireExerciseBundle\Entity;

use SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\OwnerExerciseModel;
use SimpleIT\ClaireExerciseBundle\Entity\Test\TestModel;
use SimpleIT\ClaireExerciseBundle\Entity\Test\TestModelPosition;

/**
 * Class to manage the creation of TestModelPosition
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class TestModelPositionFactory
{
    /**
     * Create a new TestModelPosition
     *
     * @param OwnerExerciseModel $oem
     * @param TestModel          $testModel
     * @param int                $position
     *
     * @return TestModelPosition
     */
    public static function create(OwnerExerciseModel $oem, TestModel $testModel, $position)
    {
        $testModelPosition = new TestModelPosition();
        $testModelPosition->setPosition($position);
        $testModelPosition->setOwnerExerciseModel($oem);
        $testModelPosition->setTestModel($testModel);

        return $testModelPosition;
    }
}
