<?php

namespace SimpleIT\ClaireExerciseBundle\Entity;

use SimpleIT\ClaireExerciseBundle\Entity\CreatedExercise\StoredExercise;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\ExerciseModel;

/**
 * Class to manage the creation of StoredExercise
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class StoredExerciseFactory
{
    /**
     * Create a new StoredExercise object
     *
     * @param string             $content Content
     * @param ExerciseModel $exerciseModel
     *
     * @return StoredExercise
     */
    public static function create($content, ExerciseModel $exerciseModel)
    {
        $storedExercise = new StoredExercise();
        $storedExercise->setContent($content);
        $storedExercise->setExerciseModel($exerciseModel);

        return $storedExercise;
    }
}
