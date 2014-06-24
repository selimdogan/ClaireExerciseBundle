<?php

namespace SimpleIT\ClaireExerciseBundle\Entity;

use SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge\Metadata;
use SimpleIT\ClaireExerciseBundle\Model\Resources\MetadataResource;

/**
 * Class to manage the creation of StoredExercise
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class KnowledgeMetadataFactory
{
    /**
     * Create a metadata
     *
     * @param string $key   Key
     * @param string $value Value
     *
     * @return Metadata
     */
    static public function create($key = null, $value = null)
    {
        $metadata = new Metadata();
        $metadata->setKey($key);
        $metadata->setValue($value);

        return $metadata;
    }

    /**
     * Create a metadata form resource
     *
     * @param MetadataResource $metadataResource Metadata resource
     *
     * @return Metadata
     */
    static public function createFromResource(MetadataResource $metadataResource)
    {
        $metadata = new Metadata();
        $metadata->setKey($metadataResource->getKey());
        $metadata->setvalue($metadataResource->getValue());

        return $metadata;
    }
}
