<?php
namespace SimpleIT\ClaireExerciseBundle\Model\Resources;

use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializerBuilder;
use SimpleIT\ClaireExerciseBundle\Entity\CreatedExercise\StoredExercise;
use SimpleIT\ClaireExerciseBundle\Serializer\Handler\AbstractClassForExerciseHandler;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseObject;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ItemResource;
use SimpleIT\Utils\Collection\PaginatorInterface;

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
     * @param PaginatorInterface $exercises
     *
     * @return array
     */
    public static function createCollection(PaginatorInterface $exercises)
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
     *
     * @return ExerciseResource
     */
    public static function create(StoredExercise $exercise)
    {
        $exerciseResource = new ExerciseResource();
        $exerciseResource->setId($exercise->getId());
        $exerciseResource->setOwnerExerciseModel($exercise->getOwnerExerciseModel()->getId());

        $serializer = SerializerBuilder::create()
            ->addDefaultHandlers()
            ->configureHandlers(
                function (HandlerRegistry $registry) {
                    $registry->registerSubscribingHandler(new AbstractClassForExerciseHandler());
                }
            )
            ->build();
        $content = $serializer->deserialize(
            $exercise->getContent(),
            ExerciseResource::getClass(
                $exercise->getOwnerExerciseModel()->getExerciseModel()
                    ->getType()
            ),
            'json'
        );

        $exerciseResource->setContent($content);

        return $exerciseResource;
    }
}
