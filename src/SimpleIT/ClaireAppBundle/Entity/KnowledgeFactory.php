<?php

namespace SimpleIT\ExerciseBundle\Entity;

use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use SimpleIT\ApiBundle\Serializer\Handler\AbstractClassForExerciseHandler;
use SimpleIT\ApiResourcesBundle\Exercise\KnowledgeResource;
use SimpleIT\ExerciseBundle\Entity\DomainKnowledge\Knowledge;

/**
 * Class to manage the creation of Knowledge
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class KnowledgeFactory
{
    /**
     * Create a new Knowledge object
     *
     * @param string $content Content
     *
     * @return Knowledge
     */
    public static function create($content = '')
    {
        $knowledge = new Knowledge();
        $knowledge->setContent($content);

        return $knowledge;
    }

    /**
     * Create a knowledge entity from a knowledge resource and the author
     *
     * @param KnowledgeResource $knowledgeResource
     *
     * @return Knowledge
     */
    public static function createFromResource(
        KnowledgeResource $knowledgeResource
    )
    {
        $knowledge = new Knowledge();
        $knowledge->setId($knowledgeResource->getId());
        $knowledge->setType($knowledgeResource->getType());

        $serializer = SerializerBuilder::create()
            ->addDefaultHandlers()
            ->configureHandlers(
                function (HandlerRegistry $registry) {
                    $registry->registerSubscribingHandler(new AbstractClassForExerciseHandler());
                }
            )
            ->build();
        $context = SerializationContext::create();

        $context->setGroups(array('knowledge_storage', 'Default'));
        $content = $serializer->serialize($knowledgeResource->getContent(), 'json', $context);
        $knowledge->setContent($content);

        return $knowledge;
    }
}
