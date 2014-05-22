<?php

namespace SimpleIT\ClaireExerciseBundle\Repository\Exercise\ExerciseModel;

use SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge\Knowledge;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\ExerciseModel;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseResource\ExerciseResource;
use SimpleIT\ClaireExerciseBundle\Exception\EntityAlreadyExistsException;
use SimpleIT\ClaireExerciseBundle\Exception\EntityDeletionException;
use SimpleIT\ClaireExerciseBundle\Repository\Exercise\SharedEntity\SharedEntityRepository;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;

/**
 * ExerciseModel repository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ExerciseModelRepository extends SharedEntityRepository
{
    /**
     * Find a model by id
     *
     * @param mixed $exerciseModelId
     *
     * @return ExerciseModel
     * @throws NonExistingObjectException
     */
    public function find($exerciseModelId)
    {
        $exerciseModel = parent::find($exerciseModelId);
        if ($exerciseModel === null) {
            throw new NonExistingObjectException();
        }

        return $exerciseModel;
    }

    /**
     * Add a required resource to an exercise model
     *
     * @param int              $exerciseModelId
     * @param ExerciseResource $requiredResource
     *
     * @throws EntityAlreadyExistsException
     */
    public function addRequiredResource($exerciseModelId, ExerciseResource $requiredResource)
    {
        parent::addRequired(
            $exerciseModelId,
            $requiredResource,
            'claire_exercise_model_resource_requirement',
            'resource'
        );
    }

    /**
     * Delete a requires resource
     *
     * @param int              $exerciseModelId
     * @param ExerciseResource $requiredResource
     *
     * @throws EntityDeletionException
     */
    public function deleteRequiredResource($exerciseModelId, ExerciseResource $requiredResource)
    {
        parent::deleteRequired(
            $exerciseModelId,
            $requiredResource,
            'claire_exercise_model_resource_requirement',
            'model_id',
            'required_resource_id'
        );
    }

    /**
     * Add a required knowledge to an exercise model
     *
     * @param int       $exerciseModelId
     * @param Knowledge $requiredKnowledge
     *
     * @throws EntityAlreadyExistsException
     */
    public function addRequiredKnowledge($exerciseModelId, Knowledge $requiredKnowledge)
    {
        parent::addRequired(
            $exerciseModelId,
            $requiredKnowledge,
            'claire_exercise_model_knowledge_requirement',
            'knowledge'
        );
    }

    /**
     * Delete a requires resource
     *
     * @param int       $exerciseModelId
     * @param Knowledge $requiredKnowledge
     *
     * @throws EntityDeletionException
     */
    public function deleteRequiredKnowledge($exerciseModelId, Knowledge $requiredKnowledge)
    {
        parent::deleteRequired(
            $exerciseModelId,
            $requiredKnowledge,
            'claire_exercise_model_knowledge_requirement',
            'model_id',
            'required_knowledge_id'
        );
    }
}
