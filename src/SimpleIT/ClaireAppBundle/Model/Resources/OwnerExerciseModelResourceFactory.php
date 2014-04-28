<?php
namespace SimpleIT\ExerciseBundle\Model\Resources;

use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializerBuilder;
use SimpleIT\ApiBundle\Serializer\Handler\AbstractClassForExerciseHandler;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModelResource;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseObject;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource;
use SimpleIT\ApiResourcesBundle\Exercise\ItemResource;
use SimpleIT\ApiResourcesBundle\Exercise\OwnerExerciseModelResource;
use SimpleIT\ExerciseBundle\Entity\ExerciseModel\Metadata;
use SimpleIT\ExerciseBundle\Entity\ExerciseModel\OwnerExerciseModel;
use SimpleIT\Utils\Collection\PaginatorInterface;

/**
 * Class OwnerExerciseModelResourceFactory
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class OwnerExerciseModelResourceFactory
{

    /**
     * Create an OwnerExerciseModelResource collection
     *
     * @param PaginatorInterface $ownerExModels
     *
     * @return array
     */
    public static function createCollection(PaginatorInterface $ownerExModels)
    {
        $ownerExModelResources = array();
        foreach ($ownerExModels as $oem) {
            $ownerExModelResources[] = self::create($oem);
        }

        return $ownerExModelResources;
    }

    /**
     * Create an OwnerExerciseModel Resource
     *
     * @param OwnerExerciseModel $oem
     *
     * @return OwnerExerciseModelResource
     */
    public static function create(OwnerExerciseModel $oem)
    {
        $oemResource = new OwnerExerciseModelResource();
        $oemResource->setId($oem->getId());
        $oemResource->setOwner($oem->getOwner()->getId());
        $oemResource->setPublic($oem->getPublic());

        $oemResource->setExerciseModel($oem->getExerciseModel()->getId());

        $metadataArray = array();
        foreach ($oem->getMetadata() as $md) {
            /** @var Metadata $md */
            $metadataArray[$md->getKey()] = $md->getValue();
        }

        $oemResource->setMetadata($metadataArray);

        $type = $oem->getExerciseModel()->getType();
        $oemResource->setType($type);
        $oemResource->setTitle($oem->getExerciseModel()->getTitle());
        $oemResource->setDraft($oem->getExerciseModel()->getDraft());
        $oemResource->setComplete($oem->getExerciseModel()->getComplete());

        $serializer = SerializerBuilder::create()
            ->addDefaultHandlers()
            ->configureHandlers(
                function (HandlerRegistry $registry) {
                    $registry->registerSubscribingHandler(new AbstractClassForExerciseHandler());
                }
            )
            ->build();
        $content = $serializer->deserialize(
            $oem->getExerciseModel()->getContent(),
            ExerciseModelResource::getClass($type),
            'json'
        );
        $oemResource->setContent($content);

        return $oemResource;
    }
}
