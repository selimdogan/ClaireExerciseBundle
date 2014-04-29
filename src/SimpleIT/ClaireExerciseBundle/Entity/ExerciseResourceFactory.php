<?php

namespace SimpleIT\ClaireExerciseBundle\Entity;

use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseResource\ExerciseResource;
use SimpleIT\ClaireExerciseBundle\Serializer\Handler\AbstractClassForExerciseHandler;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ResourceResource;

/**
 * Class to manage the creation of ExerciseResource
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class ExerciseResourceFactory
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
        $exerciseResource->setContent($content);

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
        $exerciseResource->setId($resourceResource->getId());
        $exerciseResource->setType($resourceResource->getType());

        $serializer = SerializerBuilder::create()
            ->addDefaultHandlers()
            ->configureHandlers(
                function (HandlerRegistry $registry) {
                    $registry->registerSubscribingHandler(new AbstractClassForExerciseHandler());
                }
            )
            ->build();
        $context = SerializationContext::create();

        $context->setGroups(array('resource_storage', 'Default'));
        $content = $serializer->serialize($resourceResource->getContent(), 'json', $context);
        $exerciseResource->setContent($content);

        return $exerciseResource;
    }
}
