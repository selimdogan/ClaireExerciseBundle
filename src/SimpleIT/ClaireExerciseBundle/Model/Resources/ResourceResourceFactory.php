<?php
namespace SimpleIT\ClaireExerciseBundle\Model\Resources;

use JMS\Serializer\Handler\HandlerRegistry;
use SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge\Knowledge;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseResource\ExerciseResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ResourceResource;

/**
 * Class ResourceResourceFactory
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class ResourceResourceFactory extends SharedResourceFactory
{

    /**
     * Create an ResourceResource collection
     *
     * @param array $resources
     *
     * @return array
     */
    public static function createCollection(array $resources)
    {
        $resourceResources = array();
        foreach ($resources as $resource) {
            $resourceResources[] = self::create($resource, true);
        }

        return $resourceResources;
    }

    /**
     * Create a ResourceResource
     *
     * @param ExerciseResource $resource
     * @param bool             $light
     *
     * @return ResourceResource
     */
    public static function create(ExerciseResource $resource, $light = false)
    {
        $resourceResource = new ResourceResource();
        parent::fill(
            $resourceResource,
            $resource,
            $light
        );

        if (!$light) {
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
        }

        return $resourceResource;
    }
}
