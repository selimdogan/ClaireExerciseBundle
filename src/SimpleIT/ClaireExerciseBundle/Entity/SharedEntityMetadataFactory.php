<?php

namespace SimpleIT\ClaireExerciseBundle\Entity;

use SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\Metadata as ModelMetadata;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseResource\Metadata as ResourceMetadata;
use SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge\Metadata as KnowledgeMetadata;
use SimpleIT\ClaireExerciseBundle\Model\Resources\MetadataResource;

/**
 * Class SharedEntityMetadataFactory
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class SharedEntityMetadataFactory
{

    const EXERCISE_MODEL = 'exerciseModel';

    const RESOURCE = 'resource';

    const KNOWLEDGE = 'knowledge';

    /**
     * Create a metadata
     *
     * @param string $entityType
     * @param string $key   Key
     * @param string $value Value
     *
     * @return KnowledgeMetadata|ModelMetadata|ResourceMetadata
     */
    static public function create($entityType, $key = null, $value = null)
    {
        $metadata = self::newFromEntityType($entityType);
        $metadata->setKey($key);
        $metadata->setValue($value);

        return $metadata;
    }

    /**
     * Create a metadata form resource
     *
     * @param string           $entityType
     * @param MetadataResource $metadataResource Metadata resource
     *
     * @return KnowledgeMetadata|ModelMetadata|ResourceMetadata
     */
    static public function createFromResource($entityType, MetadataResource $metadataResource)
    {
        $metadata = self::newFromEntityType($entityType);
        $metadata->setKey($metadataResource->getKey());
        $metadata->setvalue($metadataResource->getValue());

        return $metadata;
    }

    /**
     * Create a metadata from type
     *
     * @param $entityType
     *
     * @return KnowledgeMetadata|ModelMetadata|ResourceMetadata
     * @throws \LogicException
     */
    static private function newFromEntityType($entityType)
    {
        switch ($entityType) {
            case self::EXERCISE_MODEL:
                return new ModelMetadata();
            case self::RESOURCE:
                return new ResourceMetadata();
            case self::KNOWLEDGE:
                return new KnowledgeMetadata();
            default:
                throw new \LogicException('Invalid type of entity:' . $entityType);
        }
    }
}
