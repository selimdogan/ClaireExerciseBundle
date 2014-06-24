<?php
namespace SimpleIT\ClaireExerciseBundle\Model\Resources;

use SimpleIT\ClaireExerciseBundle\Entity\Test\TestAttempt;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseObject;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ItemResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\TestAttemptResource;

/**
 * Class TestAttemptResourceFactory
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class TestAttemptResourceFactory
{

    /**
     * Create a TestResource collection
     *
     * @param array $testAttempts
     *
     * @return array
     */
    public static function createCollection(array $testAttempts)
    {
        $testAttemptResources = array();
        foreach ($testAttempts as $testAttempt) {
            $testAttemptResources[] = self::create($testAttempt);
        }

        return $testAttemptResources;
    }

    /**
     * Create a TestAttemptResource
     *
     * @param TestAttempt $testAttempt
     *
     * @return TestAttemptResource
     */
    public static function create(TestAttempt $testAttempt)
    {
        $testAttemptResource = new TestAttemptResource();
        $testAttemptResource->setId($testAttempt->getId());
        $testAttemptResource->setCreatedAt($testAttempt->getCreatedAt());
        $testAttemptResource->setTest($testAttempt->getTest()->getId());

        if (is_null($testAttempt->getUser())) {
            $testAttemptResource->setUser(null);
        } else {
            $testAttemptResource->setUser($testAttempt->getUser()->getId());
        }

        return $testAttemptResource;
    }
}
