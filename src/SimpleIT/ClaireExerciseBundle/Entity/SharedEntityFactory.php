<?php

namespace SimpleIT\ClaireExerciseBundle\Entity;

use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\ExerciseModel;
use SimpleIT\ClaireExerciseBundle\Entity\SharedEntity\SharedEntity;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModelResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\SharedResource;
use SimpleIT\ClaireExerciseBundle\Serializer\Handler\AbstractClassForExerciseHandler;

/**
 * Class to manage the creation of ExerciseModel
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class SharedEntityFactory
{
    /**
     * Initialize an entity
     *
     * @param SharedEntity $entity
     * @param string       $content
     */
    protected static function initialize(&$entity, $content = '')
    {
        $entity = new ExerciseModel();
        $entity->setContent($content);
        $entity->setComplete(false);
        $entity->setPublic(false);
        $entity->setArchived(false);
    }

    /**
     * Fill an entity from a resource
     *
     * @param SharedEntity   $entity
     * @param SharedResource $resource
     * @param string         $serializationGroup
     */
    public static function fillFromResource(
        SharedEntity &$entity,
        &$resource,
        $serializationGroup = 'Default'
    )
    {
        $entity->setId($resource->getId());
        $entity->setType($resource->getType());
        $entity->setTitle($resource->getTitle());
        $entity->setPublic($resource->getPublic());
        $entity->setArchived($resource->getArchived());
        $entity->setDraft($resource->getDraft());
        $entity->setComplete($resource->getComplete());

        // content
        $serializer = SerializerBuilder::create()
            ->addDefaultHandlers()
            ->configureHandlers(
                function (HandlerRegistry $registry) {
                    $registry->registerSubscribingHandler(new AbstractClassForExerciseHandler());
                }
            )
            ->build();
        $context = SerializationContext::create();
        $context->setGroups(array($serializationGroup, 'Default'));
        $content = $serializer->serialize($resource->getContent(), 'json', $context);
        $entity->setContent($content);
    }
}
