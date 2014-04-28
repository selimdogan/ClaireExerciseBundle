<?php

namespace SimpleIT\ExerciseBundle\Service\CreatedExercise;

use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\CoreBundle\Services\TransactionalService;
use SimpleIT\ExerciseBundle\Entity\AttemptFactory;
use SimpleIT\ExerciseBundle\Entity\CreatedExercise\Attempt;
use SimpleIT\ExerciseBundle\Repository\CreatedExercise\AttemptRepository;
use SimpleIT\ExerciseBundle\Service\Test\TestAttemptServiceInterface;
use SimpleIT\UserBundle\Service\UserServiceInterface;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\CoreBundle\Annotation\Transactional;
use SimpleIT\Utils\Collection\PaginatorInterface;

/**
 * Service which manages the attempt
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class AttemptService extends TransactionalService implements AttemptServiceInterface
{
    /**
     * @var AttemptRepository
     */
    private $attemptRepository;

    /**
     * @var StoredExerciseServiceInterface
     */
    private $storedExerciseService;

    /**
     * @var TestAttemptServiceInterface
     */
    private $testAttemptService;

    /**
     * @var UserServiceInterface
     */
    private $userService;

    /**
     * Set attemptRepository
     *
     * @param AttemptRepository
     */
    public function setAttemptRepository($attemptRepository)
    {
        $this->attemptRepository = $attemptRepository;
    }

    /**
     * Set storedExerciseService
     *
     * @param StoredExerciseServiceInterface $storedExerciseService
     */
    public function setStoredExerciseService($storedExerciseService)
    {
        $this->storedExerciseService = $storedExerciseService;
    }

    /**
     * Set testAttemptService
     *
     * @param TestAttemptServiceInterface $testAttemptService
     */
    public function setTestAttemptService($testAttemptService)
    {
        $this->testAttemptService = $testAttemptService;
    }

    /**
     * Set userService
     *
     * @param UserServiceInterface $userService
     */
    public function setUserService($userService)
    {
        $this->userService = $userService;
    }

    /**
     * Find an attempt by its id
     *
     * @param int $attemptId
     *
     * @throws NonExistingObjectException
     * @return Attempt
     */
    public function get($attemptId)
    {
        $attempt = $this->attemptRepository->find($attemptId);
        if (is_null($attempt)) {
            throw new NonExistingObjectException();
        }

        return $attempt;
    }

    /**
     * Add a new attempt to the database
     *
     * @param int $exerciseId
     * @param int $userId
     * @param int $testAttemptId
     * @param int $position
     *
     * @return Attempt
     * @Transactional
     */
    public function add($exerciseId, $userId, $testAttemptId = null, $position = null)
    {
        $exercise = $this->storedExerciseService->get($exerciseId);
        $user = $this->userService->get($userId);

        $testAttempt = null;
        if (!is_null($testAttemptId)) {
            $testAttempt = $this->testAttemptService->get($testAttemptId);
        }

        $attempt = AttemptFactory::create($exercise, $user, $testAttempt, $position);
        $attempt = $this->attemptRepository->insert($attempt);

        return $attempt;
    }

    /**
     * Get all the attempts
     *
     * @param CollectionInformation $collectionInformation
     * @param null                  $userId
     * @param int                   $exerciseId
     * @param int                   $testAttemptId
     *
     * @return PaginatorInterface
     */
    public function getAll(
        CollectionInformation $collectionInformation,
        $userId = null,
        $exerciseId = null,
        $testAttemptId = null
    )
    {
        $exercise = null;
        if (!is_null($exerciseId)) {
            $exercise = $this->storedExerciseService->get($exerciseId);
        }

        $testAttempt = null;
        if (!is_null($testAttemptId)) {
            $testAttempt = $this->testAttemptService->get($testAttemptId);
        }

        return $this->attemptRepository->findAllBy(
            $collectionInformation,
            $userId,
            $exercise,
            $testAttempt
        );
    }
}
