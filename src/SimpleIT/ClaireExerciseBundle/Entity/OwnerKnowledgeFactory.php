<?php

namespace SimpleIT\ClaireExerciseBundle\Entity;

use JMS\Serializer\SerializationContext;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\OwnerKnowledgeResource;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ResourceResource;
use SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge\OwnerKnowledge;

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
