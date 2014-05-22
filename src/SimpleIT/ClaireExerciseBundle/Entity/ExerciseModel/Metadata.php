<?php

namespace SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel;

use SimpleIT\ClaireExerciseBundle\Entity\SharedEntity\Metadata as BaseMetadata;

/**
 * Exercise Model Metadata entity
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class Metadata extends BaseMetadata
{
    /**
     * @var ExerciseModel
     */
    private $exerciseModel;

    /**
     * Set exerciseModel
     *
     * @param \SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\ExerciseModel $exerciseModel
     */
    public function setExerciseModel($exerciseModel)
    {
        $this->exerciseModel = $exerciseModel;
    }

    /**
     * Get exerciseModel
     *
     * @return \SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\ExerciseModel
     */
    public function getExerciseModel()
    {
        return $this->exerciseModel;
    }

    /**
     * Set the exercise model
     *
     * @param ExerciseModel $entity
     */
    public function setEntity($entity)
    {
        $this->exerciseModel = $entity;
    }
}
