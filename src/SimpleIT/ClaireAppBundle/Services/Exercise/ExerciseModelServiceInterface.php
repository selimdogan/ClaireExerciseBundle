<?php


namespace SimpleIT\ClaireAppBundle\Services\Exercise;

/**
 * Interface ExerciseModelServiceInterface
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
interface ExerciseModelServiceInterface
{
    /**
     * Get an exercise model
     *
     * @param int $exerciseModelId Exercise Model Id
     *
     * @return \SimpleIT\ApiResourcesBundle\Exercise\ExerciseModelResource
     */
    public function get($exerciseModelId);
}
