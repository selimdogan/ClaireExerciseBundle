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
            $metadataResources[] = self::create($metadata);
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
        $metadataResource = new MetadataResource();
        $metadataResource->setKey($metadata->getKey());
        $metadataResource->setValue($metadata->getValue());

        return $metadataResource;
    }

    /**
     * Create Metadata Resource
     *
     * @param string $key
     * @param string $value
     *
     * @return MetadataResource
     */
    public static function createFromKeyValue($key, $value)
    {
        $metadataResource = new MetadataResource();
        $metadataResource->setKey($key);
        $metadataResource->setValue($value);

        return $metadataResource;
    }
}
