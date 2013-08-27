<?php


namespace SimpleIT\ClaireAppBundle\Services\Exercise;

/**
 * Interface ExerciseModelServiceInterface
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
interface ExerciseModelServiceInterface
{
    /**
     * Get an exercise model
     *
     * @param int $exerciseId Exercise Model Id
     *
     * @return \SimpleIT\ApiResourcesBundle\Exercise\ExerciseModelResource
     */
    public function get($exerciseId);
}
