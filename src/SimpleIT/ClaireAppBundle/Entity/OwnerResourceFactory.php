<?php

namespace SimpleIT\ExerciseBundle\Entity;

use JMS\Serializer\SerializationContext;
use SimpleIT\ApiResourcesBundle\Exercise\OwnerResourceResource;
use SimpleIT\ApiResourcesBundle\Exercise\ResourceResource;
use SimpleIT\ExerciseBundle\Entity\ExerciseResource\OwnerResource;

/**
 * Class OwnerResourceFactory
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class OwnerResourceFactory
{
    /**
     * Create an ownerResource entity from an ownerResourceResource and the owner
     *
     * @param OwnerResourceResource $ownerResourceResource
     *
     * @return OwnerResource
     */
    public static function createFromResource(OwnerResourceResource $ownerResourceResource)
    {
        $ownerResource = new OwnerResource();
        $ownerResource->setId($ownerResourceResource->getId());
        $ownerResource->setPublic($ownerResourceResource->getPublic());

        return $ownerResource;
    }
}
