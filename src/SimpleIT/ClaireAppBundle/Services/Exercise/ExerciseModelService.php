<?php


namespace SimpleIT\ClaireAppBundle\Services\Exercise;

use SimpleIT\ClaireAppBundle\Repository\Exercise\ExerciseModelRepository;

/**
 * Class ExerciseModelService
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class ExerciseModelService
{
    /**
     * @var  ExerciseModelRepository
     */
    private $exerciseModelRepository;

    /**
     * Set exerciseModelRepository
     *
     * @param \SimpleIT\ClaireAppBundle\Repository\Exercise\ExerciseModelRepository $exerciseModelRepository
     */
    public function setExerciseModelRepository($exerciseModelRepository)
    {
        $this->exerciseModelRepository = $exerciseModelRepository;
    }

    /**
     * Get an exercise model
     *
     * @param int $exerciseModelId Exercise Model Id
     *
     * @return \SimpleIT\ApiResourcesBundle\Exercise\ExerciseModelResource
     */
    public function get($exerciseModelId)
    {
        return $this->exerciseModelRepository->find($exerciseModelId);
    }
}
