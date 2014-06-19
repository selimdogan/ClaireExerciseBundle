<?php

namespace SimpleIT\ClaireExerciseBundle\Service\Exercise\CreatedExercise;

use SimpleIT\ClaireExerciseBundle\Entity\AttemptFactory;
use SimpleIT\ClaireExerciseBundle\Entity\CreatedExercise\Attempt;
use SimpleIT\ClaireExerciseBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Repository\Exercise\CreatedExercise\AttemptRepository;
use SimpleIT\ClaireExerciseBundle\Service\Exercise\Test\TestAttemptServiceInterface;
use SimpleIT\ClaireExerciseBundle\Service\TransactionalService;
use SimpleIT\ClaireExerciseBundle\Service\User\UserServiceInterface;
use SimpleIT\Utils\Collection\CollectionInformation;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
     * @param int $userId
     *
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @throws \SimpleIT\ClaireExerciseBundle\Exception\NonExistingObjectException
     * @return Attempt
     */
    public function get($attemptId, $userId = null)
    {
        $attempt = $this->attemptRepository->find($attemptId);
        if (is_null($attempt)) {
            throw new NonExistingObjectException();
        } elseif ($userId !== null && $attempt->getUser()->getId() !== $userId) {
            throw new AccessDeniedException();
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

        $this->em->persist($attempt);
        $this->em->flush();

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
     * @return array
     */
    public function getAll(
        $collectionInformation = null,
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
