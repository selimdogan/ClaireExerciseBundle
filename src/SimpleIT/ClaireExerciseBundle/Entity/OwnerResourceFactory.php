<?php

namespace SimpleIT\ClaireExerciseBundle\Entity;

use JMS\Serializer\SerializationContext;
use SimpleIT\ClaireExerciseBundle\Model\Resources\OwnerResourceResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ResourceResource;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseResource\OwnerResource;

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
