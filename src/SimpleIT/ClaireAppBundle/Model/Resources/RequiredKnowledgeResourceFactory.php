<?php
namespace SimpleIT\ExerciseBundle\Model\Resources;

use SimpleIT\ExerciseBundle\Entity\DomainKnowledge\Knowledge;

/**
 * Class RequiredKnowledgeResourceFactory
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class RequiredKnowledgeResourceFactory
{
    /**
     * Create a Metadata Resources collection
     *
     * @param mixed $knowledges ExerciseResources
     *
     * @return array
     */
    public static function createCollection($knowledges = array())
    {
        $requiredKnowledgeResources = array();
        foreach ($knowledges as $knowledge) {
            /** @var Knowledge $knowledge */
            $requiredKnowledgeResources[] = $knowledge->getId();
        }

        return $requiredKnowledgeResources;
    }
}
