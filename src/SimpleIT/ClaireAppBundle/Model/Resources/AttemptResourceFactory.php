<?php
namespace SimpleIT\ExerciseBundle\Model\Resources;

use SimpleIT\ApiResourcesBundle\Exercise\AttemptResource;
use SimpleIT\ExerciseBundle\Entity\CreatedExercise\Attempt;
use SimpleIT\UserBundle\Model\Resources\UserResourceFactory;
use SimpleIT\Utils\Collection\PaginatorInterface;

/**
 * Class AttemptResourceFactory
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class AttemptResourceFactory
{

    /**
     * Create an AttemptResourceFactory collection
     *
     * @param PaginatorInterface $attempts
     *
     * @return array
     */
    public static function createCollection(PaginatorInterface $attempts)
    {
        $attemptResources = array();
        foreach ($attempts as $attempt) {
            $attemptResources[] = self::create($attempt);
        }

        return $attemptResources;
    }

    /**
     * Create an AttemptResource
     *
     * @param Attempt $attempt
     *
     * @return AttemptResource
     */
    public static function create(Attempt $attempt)
    {
        $attemptResource = new AttemptResource();
        $attemptResource->setId($attempt->getId());

        if (is_null($attempt->getTestAttempt())) {
            $attemptResource->setTestAttempt(null);
        } else {
            $attemptResource->setTestAttempt($attempt->getTestAttempt()->getId());
        }
        $attemptResource->setExercise($attempt->getExercise()->getId());

        if (is_null($attempt->getUser())) {
            $attemptResource->setUser(null);
        } else {
            $attemptResource->setUser($attempt->getUser()->getId());
        }

        $attemptResource->setPosition($attempt->getPosition());

        return $attemptResource;
    }
}
