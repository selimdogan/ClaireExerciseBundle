<?php
namespace SimpleIT\ClaireExerciseBundle\Model\Resources;

use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializerBuilder;
use SimpleIT\ClaireExerciseBundle\Entity\CreatedExercise\StoredExercise;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseObject;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ItemResource;
use SimpleIT\ClaireExerciseBundle\Serializer\Handler\AbstractClassForExerciseHandler;

/**
 * Class ExerciseModelResourceFactory
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class ExerciseResourceFactory
{

    /**
     * Create an ExerciseResource collection
     *
     * @param array $exercises
     *
     * @return array
     */
    public static function createCollection(array $exercises)
    {
        $exerciseResources = array();
        foreach ($exercises as $exercise) {
            $exerciseResources[] = self::create($exercise);
        }

        return $exerciseResources;
    }

    /**
     * Create an Exercise Resource
     *
     * @param StoredExercise $exercise
     * @param bool           $links
     *
     * @return ExerciseResource
     */
    public static function create(StoredExercise $exercise, $links = false)
    {
        $exerciseResource = new ExerciseResource();
        $exerciseResource->setId($exercise->getId());
        $exerciseResource->setExerciseModel($exercise->getExerciseModel()->getId());
        $exerciseResource->setType($exercise->getExerciseModel()->getType());
        $exerciseResource->setTitle($exercise->getExerciseModel()->getTitle());

        $serializer = SerializerBuilder::create()
            ->addDefaultHandlers()
            ->configureHandlers(
                function (HandlerRegistry $registry) {
                    $registry->registerSubscribingHandler(
                        new AbstractClassForExerciseHandler()
                    );
                }
            )
            ->build();
        $content = $serializer->deserialize(
            $exercise->getContent(),
            ExerciseResource::getClass($exercise->getExerciseModel()->getType()),
            'json'
        );

        $exerciseResource->setContent($content);

        if ($links)
        {
            $attempts = array();
            foreach ($exercise->getAttempts() as $attempt)
            {
                $attempts[] = AttemptResourceFactory::create($attempt, true);
            }
            $exerciseResource->setAttempts($attempts);
        }

        return $exerciseResource;
    }
}
