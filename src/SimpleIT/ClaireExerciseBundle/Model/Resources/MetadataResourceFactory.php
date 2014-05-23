<?php
namespace SimpleIT\ClaireExerciseBundle\Model\Resources;

use SimpleIT\ClaireExerciseBundle\Entity\SharedEntity\Metadata;
use SimpleIT\ClaireExerciseBundle\Model\Resources\MetadataResource;

/**
 * Class ResourceMetadataResourceFactory
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class MetadataResourceFactory
{
    /**
     * Create a Metadata Resources collection
     *
     * @param mixed $metadatas Metadatas
     *
     * @return array
     */
    public static function createCollection($metadatas = array())
    {
        $metadataResources = array();
        foreach ($metadatas as $metadata) {
            /** @var Metadata $metadata */
            $metadataResources[$metadata->getKey()] = $metadata->getValue();
        }

        return $metadataResources;
    }

    /**
     * Create Metadata Resource
     *
     * @param Metadata $metadata Metadata
     *
     * @return MetadataResource
     */
    public static function create($metadata)
    {
        $metadataResource = array($metadata->getKey() => $metadata->getValue());

        return $metadataResource;
    }
}
