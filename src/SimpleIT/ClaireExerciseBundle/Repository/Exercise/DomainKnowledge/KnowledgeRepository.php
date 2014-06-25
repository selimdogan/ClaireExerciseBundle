<?php

namespace SimpleIT\ClaireExerciseBundle\Repository\Exercise\DomainKnowledge;

use SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge\Knowledge;
use SimpleIT\ClaireExerciseBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Repository\Exercise\SharedEntity\SharedEntityRepository;

/**
 * Knowledge repository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class KnowledgeRepository extends SharedEntityRepository
{
    /**
     * Find a knowledge by id
     *
     * @param mixed $knowledgeId
     *
     * @return Knowledge
     * @throws NonExistingObjectException
     */
    public function find($knowledgeId)
    {
        $knowledge = parent::find($knowledgeId);
        if ($knowledge === null) {
            throw new NonExistingObjectException();
        }

        return $knowledge;
    }

    /**
     * Get the join that reduce the number of requests.
     *
     * @return array
     */
    protected function getLeftJoins()
    {
        return array(
            "rk" => "entity.requiredKnowledges"
        );
    }
}
