<?php

namespace SimpleIT\ClaireAppBundle\Services\Exercise\Attempt;

use SimpleIT\ApiResourcesBundle\Exercise\AttemptResource;
use SimpleIT\ClaireAppBundle\Repository\Exercise\Attempt\AttemptByExerciseRepository;
use SimpleIT\ClaireAppBundle\Repository\Exercise\Attempt\AttemptRepository;

/**
 * Class AttemptService
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class AttemptService
{
    /**
     * @var AttemptByExerciseRepository
     */
    private $attemptByExerciseRepository;

    /**
     * @var AttemptRepository
     */
    private $attemptRepository;

    /**
     * Set attemptByExerciseRepository
     *
     * @param AttemptByExerciseRepository $attemptByExerciseRepository
     */
    public function setAttemptByExerciseRepository($attemptByExerciseRepository)
    {
        $this->attemptByExerciseRepository = $attemptByExerciseRepository;
    }

    /**
     * Set attemptRepository
     *
     * @param AttemptRepository $attemptRepository
     */
    public function setAttemptRepository($attemptRepository)
    {
        $this->attemptRepository = $attemptRepository;
    }

    /**
     * Create a new attempt for this exercise
     *
     * @param $exerciseId
     *
     * @return AttemptResource
     */
    public function create($exerciseId)
    {
        return $this->attemptByExerciseRepository->insert($exerciseId);
    }

    /**
     * Get an attempt by its id
     *
     * @param int $attemptId
     *
     * @return AttemptResource
     */
    public function get($attemptId)
    {
        return $this->attemptRepository->find($attemptId);
    }
}
