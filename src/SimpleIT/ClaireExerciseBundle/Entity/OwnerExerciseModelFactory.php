<?php

namespace SimpleIT\ClaireExerciseBundle\Entity;

use JMS\Serializer\SerializationContext;
use SimpleIT\ClaireExerciseBundle\Model\Resources\OwnerExerciseModelResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ResourceResource;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\OwnerExerciseModel;

/**
 * Class OwnerExerciseModelFactory
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class OwnerExerciseModelFactory
{
    /**
     * Create an ownerExerciseModel entity from an ownerExerciseModelResource and the owner
     *
     * @param OwnerExerciseModelResource $ownerExerciseModelResource
     *
     * @return OwnerExerciseModel
     */
    public static function createFromResource(
        OwnerExerciseModelResource $ownerExerciseModelResource
    )
    {
        $ownerResource = new OwnerExerciseModel();
        $ownerResource->setId($ownerExerciseModelResource->getId());
        $ownerResource->setPublic($ownerExerciseModelResource->getPublic());

        return $ownerResource;
    }
}
