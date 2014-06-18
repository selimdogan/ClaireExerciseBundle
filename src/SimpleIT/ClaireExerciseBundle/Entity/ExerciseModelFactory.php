<?php

namespace SimpleIT\ClaireExerciseBundle\Entity;

use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializationContext;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\ExerciseModel;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModelResource;

/**
 * Class to manage the creation of ExerciseModel
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class ExerciseModelFactory extends SharedEntityFactory
{
    /**
     * Create a new ExerciseModel object
     *
     * @param string $content Content
     *
     * @return ExerciseModel
     */
    public static function createExerciseModel($content = '')
    {
        $exerciseModel = new ExerciseModel();
        parent::initialize($exerciseModel, $content);

        return $exerciseModel;
    }

    /**
     * Create an exerciseModel entity from a resource and the author
     *
     * @param ExerciseModelResource $modelResource
     *
     * @return ExerciseModel
     */
    public static function createFromResource(
        ExerciseModelResource $modelResource
    )
    {
        $model = new ExerciseModel();
        parent::fillFromResource($model, $modelResource, 'exercise_model_storage');

        return $model;
    }
}
