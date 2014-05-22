<?php

namespace SimpleIT\ClaireExerciseBundle\Entity\ExerciseResource;

use SimpleIT\ClaireExerciseBundle\Entity\Common\Metadata as BaseMetadata;

/**
 * Exercise Resource Metadata entity
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class Metadata extends BaseMetadata
{
    /**
     * @var ExerciseResource
     */
    private $resource;

    /**
     * Set resource
     *
     * @param ExerciseResource $resource
     */
    public function setResource($resource)
    {
        $this->resource = $resource;
    }

    /**
     * Get resource
     *
     * @return ExerciseResource
     */
    public function getResource()
    {
        return $this->resource;
    }
}
