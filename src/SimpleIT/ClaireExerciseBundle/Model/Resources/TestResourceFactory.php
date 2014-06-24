<?php
namespace SimpleIT\ClaireExerciseBundle\Model\Resources;

use SimpleIT\ClaireExerciseBundle\Entity\Test\Test;
use SimpleIT\ClaireExerciseBundle\Entity\Test\TestPosition;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseObject;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ItemResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\TestResource;

/**
 * Class TestResourceFactory
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class TestResourceFactory
{

    /**
     * Create a TestResource collection
     *
     * @param array $tests
     *
     * @return array
     */
    public static function createCollection(array $tests)
    {
        $testResources = array();
        foreach ($tests as $test) {
            $testResources[] = self::create($test);
        }

        return $testResources;
    }

    /**
     * Create a TestResource
     *
     * @param Test $test
     *
     * @return TestResource
     */
    public static function create(Test $test)
    {
        $testResource = new TestResource();
        $testResource->setId($test->getId());
        $testResource->setTestModel(
            TestModelResourceFactory::create($test->getTestModel())
        );

        $exercises = array();
        foreach ($test->getTestPositions() as $position) {
            /** @var TestPosition $position */
            $exercises[$position->getPosition()] = $position->getExercise()->getId();
        }

        $ex = array();
        for ($i = 0; $i < count($exercises); $i++) {
            $ex[] = $exercises[$i];
        }
        $testResource->setExercises($ex);

        return $testResource;
    }
}
