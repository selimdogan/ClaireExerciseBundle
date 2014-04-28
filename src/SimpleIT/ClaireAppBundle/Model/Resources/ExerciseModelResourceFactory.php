<?php
namespace SimpleIT\ExerciseBundle\Model\Resources;

use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializerBuilder;
use SimpleIT\ApiBundle\Serializer\Handler\AbstractClassForExerciseHandler;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModelResource;
use SimpleIT\ExerciseBundle\Entity\ExerciseModel\ExerciseModel;
use SimpleIT\ExerciseBundle\Entity\ExerciseResource\ExerciseResource;
use SimpleIT\Utils\Collection\PaginatorInterface;

/**
 * Class ExerciseModelResourceFactory
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class ExerciseModelResourceFactory
{

    /**
     * Create an ExerciseModel Resource collection
     *
     * @param PaginatorInterface $exerciseModels
     *
     * @return array
     */
    public static function createCollection(PaginatorInterface $exerciseModels)
    {
        $exerciseModelResources = array();
        foreach ($exerciseModels as $exerciseModel) {
            $exerciseModelResources[] = self::create($exerciseModel);
        }

        return $exerciseModelResources;
    }

    /**
     * Create an ExerciseModel Resource
     *
     * @param ExerciseModel $exerciseModel
     *
     * @return ExerciseModelResource
     */
    public static function create(ExerciseModel $exerciseModel)
    {

        $exerciseModelResource = new ExerciseModelResource();
        $exerciseModelResource->setId($exerciseModel->getId());
        $exerciseModelResource->setTitle($exerciseModel->getTitle());
        $exerciseModelResource->setType($exerciseModel->getType());
        $exerciseModelResource->setAuthor($exerciseModel->getAuthor()->getId());
        $exerciseModelResource->setDraft($exerciseModel->getDraft());
        $exerciseModelResource->setComplete($exerciseModel->getComplete());

        $serializer = SerializerBuilder::create()
            ->addDefaultHandlers()
            ->configureHandlers(
                function (HandlerRegistry $registry) {
                    $registry->registerSubscribingHandler(new AbstractClassForExerciseHandler());
                }
            )
            ->build();
        $content = $serializer->deserialize(
            $exerciseModel->getContent(),
            $exerciseModelResource::getClass($exerciseModel->getType()),
            'json'
        );
        $exerciseModelResource->setContent($content);

        $rr = array();
        foreach ($exerciseModel->getRequiredExerciseResources() as $req) {
            /** @var ExerciseResource $req */
            $rr[] = $req->getId();
        }
        $exerciseModelResource->setRequiredExerciseResources($rr);

        return $exerciseModelResource;
    }
}
