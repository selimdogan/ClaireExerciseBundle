<?php
namespace SimpleIT\ExerciseBundle\Model\Resources;

use SimpleIT\ApiResourcesBundle\Exercise\ExerciseObject;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource;
use SimpleIT\ApiResourcesBundle\Exercise\ItemResource;
use SimpleIT\ApiResourcesBundle\Exercise\TestResource;
use SimpleIT\ExerciseBundle\Entity\Test\Test;
use SimpleIT\ExerciseBundle\Entity\Test\TestPosition;
use SimpleIT\Utils\Collection\PaginatorInterface;

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
     * @param PaginatorInterface $tests
     *
     * @return array
     */
    public static function createCollection(PaginatorInterface $tests)
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
