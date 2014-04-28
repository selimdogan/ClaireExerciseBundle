<?php
namespace SimpleIT\ExerciseBundle\Model\Resources;

use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializerBuilder;
use SimpleIT\ApiBundle\Serializer\Handler\AbstractClassForExerciseHandler;
use SimpleIT\ApiResourcesBundle\Exercise\KnowledgeResource;
use SimpleIT\ApiResourcesBundle\Exercise\OwnerKnowledgeResource;
use SimpleIT\ApiResourcesBundle\Exercise\ResourceResource;
use SimpleIT\ExerciseBundle\Entity\DomainKnowledge\OwnerKnowledge;
use SimpleIT\ExerciseBundle\Entity\ExerciseResource\Metadata;
use SimpleIT\Utils\Collection\PaginatorInterface;

/**
 * Class OwnerKnowledgeResourceFactory
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class OwnerKnowledgeResourceFactory
{
    /**
     * Create an OwnerKnowledgeResource collection
     *
     * @param PaginatorInterface $ownerKnowledges
     *
     * @return array
     */
    public static function createCollection(PaginatorInterface $ownerKnowledges)
    {
        $ownerKnowledgeResources = array();
        foreach ($ownerKnowledges as $ownerKnowledge) {
            $ownerKnowledgeResources[] = self::create($ownerKnowledge);
        }

        return $ownerKnowledgeResources;
    }

    /**
     * Create an ownerKnowledgeResource
     *
     * @param OwnerKnowledge $ownerKnowledge
     *
     * @return OwnerKnowledgeResource
     */
    public static function create(OwnerKnowledge $ownerKnowledge)
    {
        $ownerKnowledgeResource = new OwnerKnowledgeResource();
        $ownerKnowledgeResource->setId($ownerKnowledge->getId());

        $ownerKnowledgeResource->setKnowledge($ownerKnowledge->getKnowledge()->getId());

        $ownerKnowledgeResource->setPublic($ownerKnowledge->getPublic());
        $ownerKnowledgeResource->setOwner($ownerKnowledge->getOwner()->getId());

        $metadataArray = array();
        foreach ($ownerKnowledge->getMetadata() as $md) {
            /** @var Metadata $md */
            $metadataArray[$md->getKey()] = $md->getValue();
        }

        $ownerKnowledgeResource->setMetadata($metadataArray);

        $type = $ownerKnowledge->getKnowledge()->getType();
        $ownerKnowledgeResource->setType($type);

        $serializer = SerializerBuilder::create()
            ->addDefaultHandlers()
            ->configureHandlers(
                function (HandlerRegistry $registry) {
                    $registry->registerSubscribingHandler(new AbstractClassForExerciseHandler());
                }
            )
            ->build();
        $content = $serializer->deserialize(
            $ownerKnowledge->getKnowledge()->getContent(),
            KnowledgeResource::getClass($type),
            'json'
        );
        $ownerKnowledgeResource->setContent($content);

        return $ownerKnowledgeResource;
    }
}
