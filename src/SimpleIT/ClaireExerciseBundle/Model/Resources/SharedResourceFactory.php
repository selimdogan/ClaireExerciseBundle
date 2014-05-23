<?php
namespace SimpleIT\ClaireExerciseBundle\Model\Resources;

use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializerBuilder;
use SimpleIT\ClaireExerciseBundle\Entity\SharedEntity\Metadata;
use SimpleIT\ClaireExerciseBundle\Entity\SharedEntity\SharedEntity;
use SimpleIT\ClaireExerciseBundle\Model\Resources\DomainKnowledge\CommonKnowledge;
use SimpleIT\ClaireExerciseBundle\Serializer\Handler\AbstractClassForExerciseHandler;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class SharableResourceFactory
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class SharedResourceFactory
{
    /**
     * @param SharedResource $resource
     * @param SharedEntity   $entity
     */
    protected static function fill(&$resource, $entity)
    {
        $resource->setId($entity->getId());
        $resource->setType($entity->getType());
        $resource->setTitle($entity->getTitle());
        $resource->setAuthor($entity->getAuthor()->getId());
        $resource->setPublic($entity->getPublic());
        $resource->setArchived($entity->getArchived());
        $resource->setOwner($entity->getOwner()->getId());

        // Parent and fork from
        if (!is_null($entity->getParent())) {
            $resource->setParent($entity->getParent()->getId());
        }
        if (!is_null($entity->getForkFrom())) {
            $resource->setForkFrom($entity->getForkFrom()->getId());
        }

        // metadata
        $metadataArray = array();
        foreach ($entity->getMetadata() as $md) {
            /** @var Metadata $md */
            $metadataArray[$md->getKey()] = $md->getValue();
        }
        $resource->setMetadata($metadataArray);

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
            $entity->getContent(),
            $resource->getSerializationClass(),
            'json'
        );
        $resource->setContent($content);
    }
}
