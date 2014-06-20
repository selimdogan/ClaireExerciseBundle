<?php

namespace SimpleIT\ClaireExerciseBundle\Repository\Exercise\ExerciseModel;

use SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\ExerciseModel;
use SimpleIT\ClaireExerciseBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Repository\Exercise\SharedEntity\SharedEntityRepository;

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
     * Get the join that reduce the number of requests.
     *
     * @return array
     */
    protected function getLeftJoins()
    {
        return array(
            "rr" => "entity.requiredExerciseResources",
            "rk" => "entity.requiredKnowledges"
        );
    }
}
