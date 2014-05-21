<?php
namespace SimpleIT\ClaireExerciseBundle\Model\Resources;

use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializerBuilder;
use SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge\Knowledge;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseResource\Metadata;
use SimpleIT\ClaireExerciseBundle\Serializer\Handler\AbstractClassForExerciseHandler;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ResourceResource;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseResource\ExerciseResource;
use SimpleIT\Utils\Collection\PaginatorInterface;

/**
 * Class ResourceResourceFactory
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class ResourceResourceFactory
{

    /**
     * Create an ResourceResource collection
     *
     * @param PaginatorInterface $resources
     *
     * @return array
     */
    public static function createCollection(PaginatorInterface $resources)
    {
        $resourceResources = array();
        foreach ($resources as $resource) {
            $resourceResources[] = self::create($resource);
        }

        return $resourceResources;
    }

    /**
     * Create a ResourceResource
     *
     * @param ExerciseResource $resource
     *
     * @return ResourceResource
     */
    public static function create(ExerciseResource $resource)
    {
        $resourceResource = new ResourceResource();
        $resourceResource->setId($resource->getId());
        $resourceResource->setType($resource->getType());
        $resourceResource->setAuthor($resource->getAuthor()->getId());
        $resourceResource->setPublic($resource->getPublic());
        $resourceResource->setArchived($resource->getArchived());
        $resourceResource->setOwner($resource->getOwner()->getId());

        // Parent and fork from
        if (!is_null($resource->getParent())) {
            $resourceResource->setParent($resource->getParent()->getId());
        }
        if (!is_null($resource->getForkFrom())) {
            $resourceResource->setForkFrom($resource->getForkFrom()->getId());
        }

        // metadata
        $metadataArray = array();
        foreach ($resource->getMetadata() as $md) {
            /** @var Metadata $md */
            $metadataArray[$md->getKey()] = $md->getValue();
        }
        $resourceResource->setMetadata($metadataArray);

        // content
        $serializer = SerializerBuilder::create()
            ->addDefaultHandlers()
            ->configureHandlers(
                function (HandlerRegistry $registry) {
                    $registry->registerSubscribingHandler(new AbstractClassForExerciseHandler());
                }
            )
            ->build();
        $content = $serializer->deserialize(
            $resource->getContent(),
            ResourceResource::getClass($resource->getType()),
            'json'
        );
        $resourceResource->setContent($content);

        // required resources
        $requirements = array();
        foreach ($resource->getRequiredExerciseResources() as $req) {
            /** @var ExerciseResource $req */
            $requirements[] = $req->getId();
        }
        $resourceResource->setRequiredExerciseResources($requirements);

        // required resources
        $requirements = array();
        foreach ($resource->getRequiredKnowledges() as $req) {
            /** @var Knowledge $req */
            $requirements[] = $req->getId();
        }
        $resourceResource->setRequiredKnowledges($requirements);

        return $resourceResource;
    }
}
