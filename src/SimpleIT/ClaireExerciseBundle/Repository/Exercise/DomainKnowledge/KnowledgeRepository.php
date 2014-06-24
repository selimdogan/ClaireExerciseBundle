<?php

namespace SimpleIT\ClaireExerciseBundle\Repository\Exercise\DomainKnowledge;

use SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge\Knowledge;
use SimpleIT\ClaireExerciseBundle\Exception\EntityAlreadyExistsException;
use SimpleIT\ClaireExerciseBundle\Exception\EntityDeletionException;
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
     * Add a required knowledge to a knowledge
     *
     * @param int       $knowledgeId
     * @param Knowledge $requiredKnowledge
     *
     * @throws EntityAlreadyExistsException
     */
    public function addRequiredKnowledge($knowledgeId, Knowledge $requiredKnowledge)
    {
        parent::addRequired(
            $knowledgeId,
            $requiredKnowledge,
            'claire_exercise_knowledge_knowledge_requirement',
            'knowledge'
        );
    }

    /**
     * Delete a required knowledge
     *
     * @param int       $knowledgeId
     * @param Knowledge $requiredKnowledge
     *
     * @throws EntityDeletionException
     */
    public function deleteRequiredKnowledge($knowledgeId, Knowledge $requiredKnowledge)
    {
        parent::deleteRequired(
            $knowledgeId,
            $requiredKnowledge,
            'claire_exercise_knowledge_knowledge_requirement',
            'knowledge_id',
            'required_id'
        );
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
