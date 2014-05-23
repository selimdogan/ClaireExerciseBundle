<?php

namespace SimpleIT\ClaireExerciseBundle\Entity;

use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializationContext;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseResource\ExerciseResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ResourceResource;

/**
 * Class to manage the creation of ExerciseResource
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class ExerciseResourceFactory extends SharedEntityFactory
{
    /**
     * Create a new ExerciseResource object
     *
     * @param string $content Content
     *
     * @return ExerciseResource
     */
    public static function create($content = '')
    {
        $exerciseResource = new ExerciseResource();
        parent::initialize($exerciseResource, $content);

        return $exerciseResource;
    }

    /**
     * Create an exerciseResource entity from a resourceResource and the author
     *
     * @param ResourceResource $resourceResource
     *
     * @return ExerciseResource
     */
    public static function createFromResource(
        ResourceResource $resourceResource
    )
    {
        $exerciseResource = new ExerciseResource();
        parent::fillFromResource($exerciseResource, $resourceResource, 'resource_storage');

        return $exerciseResource;
    }
}
