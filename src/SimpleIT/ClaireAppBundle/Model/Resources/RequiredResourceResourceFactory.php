<?php
namespace SimpleIT\ExerciseBundle\Model\Resources;

use SimpleIT\ExerciseBundle\Entity\ExerciseResource\ExerciseResource;

/**
 * Class RequiredResourceResourceFactory
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class RequiredResourceResourceFactory
{
    /**
     * Create a Metadata Resources collection
     *
     * @param mixed $resources ExerciseResources
     *
     * @return array
     */
    public static function createCollection($resources = array())
    {
        $requiredResourceResources = array();
        foreach ($resources as $resource) {
            /** @var ExerciseResource $resource */
            $requiredResourceResources[] = $resource->getId();
        }

        return $requiredResourceResources;
    }
}
