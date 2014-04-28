<?php

namespace SimpleIT\ExerciseBundle\Entity;

use JMS\Serializer\SerializationContext;
use SimpleIT\ApiResourcesBundle\Exercise\OwnerKnowledgeResource;
use SimpleIT\ApiResourcesBundle\Exercise\ResourceResource;
use SimpleIT\ExerciseBundle\Entity\DomainKnowledge\OwnerKnowledge;

/**
 * Class OwnerKnowledgeFactory
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class OwnerKnowledgeFactory
{
    /**
     * Create an ownerKnowledge entity from an ownerKnowledgeResource
     *
     * @param OwnerKnowledgeResource $ownerKnowledgeResource
     *
     * @return OwnerKnowledge
     */
    public static function createFromResource(OwnerKnowledgeResource $ownerKnowledgeResource)
    {
        $ownerKnowledge = new OwnerKnowledge();
        $ownerKnowledge->setId($ownerKnowledgeResource->getId());
        $ownerKnowledge->setPublic($ownerKnowledgeResource->getPublic());

        return $ownerKnowledge;
    }
}
