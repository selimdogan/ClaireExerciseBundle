<?php
namespace SimpleIT\ClaireExerciseBundle\Model\Resources;

use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializerBuilder;
use SimpleIT\ClaireExerciseBundle\Serializer\Handler\AbstractClassForExerciseHandler;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\OwnerResourceResource;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ResourceResource;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseResource\Metadata;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseResource\OwnerResource;
use SimpleIT\Utils\Collection\PaginatorInterface;

/**
 * Class OwnerResourceFactory
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class OwnerResourceResourceFactory
{

    /**
     * Create an OwnerResourceResource collection
     *
     * @param PaginatorInterface $ownerResources
     *
     * @return array
     */
    public static function createCollection(PaginatorInterface $ownerResources)
    {
        $ownerResourceResources = array();
        foreach ($ownerResources as $ownerResource) {
            $ownerResourceResources[] = self::create($ownerResource);
        }

        return $ownerResourceResources;
    }

    /**
     * Create an ownerResourceResource
     *
     * @param OwnerResource $ownerResource
     *
     * @return OwnerResourceResource
     */
    public static function create(OwnerResource $ownerResource)
    {
        $ownerResourceResource = new OwnerResourceResource();
        $ownerResourceResource->setId($ownerResource->getId());

        $ownerResourceResource->setResource($ownerResource->getResource()->getId());

        $ownerResourceResource->setPublic($ownerResource->getPublic());
        $ownerResourceResource->setOwner($ownerResource->getOwner()->getId());

        $metadataArray = array();
        foreach ($ownerResource->getMetadata() as $md) {
            /** @var Metadata $md */
            $metadataArray[$md->getKey()] = $md->getValue();
        }

        $ownerResourceResource->setMetadata($metadataArray);

        $type = $ownerResource->getResource()->getType();
        $ownerResourceResource->setType($type);

        $serializer = SerializerBuilder::create()
            ->addDefaultHandlers()
            ->configureHandlers(
                function (HandlerRegistry $registry) {
                    $registry->registerSubscribingHandler(new AbstractClassForExerciseHandler());
                }
            )
            ->build();
        $content = $serializer->deserialize(
            $ownerResource->getResource()->getContent(),
            ResourceResource::getClass($type),
            'json'
        );
        $ownerResourceResource->setContent($content);

        return $ownerResourceResource;
    }
}
