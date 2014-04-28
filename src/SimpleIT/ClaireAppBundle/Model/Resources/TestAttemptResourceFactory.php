<?php
namespace SimpleIT\ExerciseBundle\Model\Resources;

use SimpleIT\ApiResourcesBundle\Exercise\ExerciseObject;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource;
use SimpleIT\ApiResourcesBundle\Exercise\ItemResource;
use SimpleIT\ApiResourcesBundle\Exercise\TestAttemptResource;
use SimpleIT\ExerciseBundle\Entity\Test\TestAttempt;
use SimpleIT\UserBundle\Model\Resources\UserResourceFactory;
use SimpleIT\Utils\Collection\PaginatorInterface;

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
     * @param PaginatorInterface $testAttempts
     *
     * @return array
     */
    public static function createCollection(PaginatorInterface $testAttempts)
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
