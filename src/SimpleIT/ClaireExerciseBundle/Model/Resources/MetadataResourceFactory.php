<?php
namespace SimpleIT\ClaireExerciseBundle\Model\Resources;

use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\MetadataResource;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseResource\Metadata as ResMD;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\Metadata as ModMD;
use SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge\Metadata as KnoMD;

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
            /** @var ResMD|ModMD $metadata */
            $metadataResources[$metadata->getKey()] = $metadata->getValue();
        }

        return $metadataResources;
    }

    /**
     * Create Metadata Resource
     *
     * @param ResMD|ModMD|KnoMD $metadata Metadata
     *
     * @return MetadataResource
     */
    public static function create($metadata)
    {
        $metadataResource = array($metadata->getKey() => $metadata->getValue());

        return $metadataResource;
    }
}
