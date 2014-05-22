<?php

namespace SimpleIT\ClaireExerciseBundle\Entity;

use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use SimpleIT\ClaireExerciseBundle\Serializer\Handler\AbstractClassForExerciseHandler;
use SimpleIT\ClaireExerciseBundle\Model\Resources\KnowledgeResource;
use SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge\Knowledge;

/**
 * Class to manage the creation of Knowledge
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class KnowledgeFactory extends SharedEntityFactory
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
        parent::initialize($knowledge, $content);

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
        parent::fillFromResource($knowledge, $knowledgeResource, 'knowledge_storage');

        return $knowledge;
    }
}
