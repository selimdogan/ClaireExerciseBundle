<?php


namespace SimpleIT\ClaireAppBundle\Services\Exercise;

use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource;

/**
 * Interface for class ExerciseService
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
interface ExerciseServiceInterface
{
    /**
     * Get an exercise
     *
     * @param int $exerciseId Exercise Id
     *
     * @return ExerciseResource
     */
    public function get($exerciseId);

    /**
     * Generate a new instance of exercise with this model
     *
     * @param int $ownerExerciseModelId
     *
     * @return ExerciseResource
     */
    public function generate($ownerExerciseModelId);
}
