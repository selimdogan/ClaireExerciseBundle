<?php

namespace SimpleIT\ClaireAppBundle\Services\Exercise\Attempt;

use SimpleIT\ApiResourcesBundle\Exercise\AttemptResource;
use SimpleIT\ClaireAppBundle\Repository\Exercise\Attempt\AttemptByExerciseRepository;
use SimpleIT\ClaireAppBundle\Repository\Exercise\Attempt\AttemptByTestAttemptRepository;
use SimpleIT\ClaireAppBundle\Repository\Exercise\Attempt\AttemptRepository;
use SimpleIT\Utils\Collection\CollectionInformation;

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
     * @var AttemptByTestAttemptRepository
     */
    private $attemptByTestAttemptRepository;

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
     * Set attemptByTestAttemptRepository
     *
     * @param \SimpleIT\ClaireAppBundle\Repository\Exercise\Attempt\AttemptByTestAttemptRepository $attemptByTestAttemptRepository
     */
    public function setAttemptByTestAttemptRepository($attemptByTestAttemptRepository)
    {
        $this->attemptByTestAttemptRepository = $attemptByTestAttemptRepository;
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

    /**
     * Get all the attempts from a test attempt
     *
     * @param int                   $testAttemptId
     * @param CollectionInformation $collectionInformation
     *
     * @return \SimpleIT\Utils\Collection\PaginatedCollection
     */
    public function getAllByTestAttempt($testAttemptId, $collectionInformation = null)
    {
        return $this->attemptByTestAttemptRepository->findAll(
            $testAttemptId,
            $collectionInformation
        );
    }
}
