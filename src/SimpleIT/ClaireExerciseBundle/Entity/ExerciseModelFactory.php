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
abstract class ExerciseModelFactory
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
        $exerciseModel->setContent($content);
        $exerciseModel->setComplete(false);
        $exerciseModel->setPublic(false);
        $exerciseModel->setArchived(false);

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
        $model->setId($modelResource->getId());
        $model->setType($modelResource->getType());
        $model->setTitle($modelResource->getTitle());
        $model->setDraft($modelResource->getDraft());
        $model->setComplete($modelResource->getComplete());
        $model->setPublic($modelResource->getPublic());
        $model->setArchived($modelResource->getArchived());

        // content
        $serializer = SerializerBuilder::create()
            ->addDefaultHandlers()
            ->configureHandlers(
                function (HandlerRegistry $registry) {
                    $registry->registerSubscribingHandler(new AbstractClassForExerciseHandler());
                }
            )
            ->build();
        $context = SerializationContext::create();
        $context->setGroups(array('exercise_model_storage', 'Default'));
        $content = $serializer->serialize($modelResource->getContent(), 'json', $context);
        $model->setContent($content);

        return $model;
    }
}
