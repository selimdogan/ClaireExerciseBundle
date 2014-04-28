<?php

namespace SimpleIT\ExerciseBundle\Entity;

use JMS\Serializer\SerializationContext;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModelResource;
use SimpleIT\ApiResourcesBundle\Exercise\TestModelResource;
use SimpleIT\CommonBundle\Entity\User;
use SimpleIT\ExerciseBundle\Entity\Test\TestModel;

/**
 * Class to manage the creation of TestModel
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class TestModelFactory
{
    /**
     * Create a new TestModel object
     *
     * @param string $title
     * @param User   $author
     *
     * @return TestModel
     */
    public static function createExerciseModel($title = '', $author = null)
    {
        $testModel = new TestModel();
        $testModel->setAuthor($author);
        $testModel->setTitle($title);

        return $testModel;
    }

    /**
     * Create an exerciseModel entity from a resource and the author
     *
     * @param TestModelResource $testModelResource
     *
     * @return TestModel
     */
    public static function createFromResource(
        TestModelResource $testModelResource
    )
    {
        $testModel = new TestModel();
        $testModel->setTitle($testModelResource->getTitle());

        return $testModel;
    }
}
