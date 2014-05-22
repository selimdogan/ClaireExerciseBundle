<?php

namespace SimpleIT\ClaireExerciseBundle\Entity;

use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use SimpleIT\ClaireExerciseBundle\Serializer\Handler\AbstractClassForExerciseHandler;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModelResource;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\ExerciseModel;

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

        $model->setDraft($modelResource->getDraft());
        $model->setComplete($modelResource->getComplete());

        return $model;
    }
}
