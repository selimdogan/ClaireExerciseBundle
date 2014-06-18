<?php

namespace SimpleIT\ClaireExerciseBundle\Service\Exercise\Test;

use SimpleIT\ClaireExerciseBundle\Entity\Test\TestAttempt;
use SimpleIT\ClaireExerciseBundle\Entity\Test\TestPosition;
use SimpleIT\ClaireExerciseBundle\Entity\TestAttemptFactory;
use SimpleIT\ClaireExerciseBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Repository\Exercise\Test\TestAttemptRepository;
use SimpleIT\ClaireExerciseBundle\Service\Exercise\CreatedExercise\AttemptService;
use SimpleIT\ClaireExerciseBundle\Service\TransactionalService;
use SimpleIT\ClaireExerciseBundle\Service\User\UserServiceInterface;
use SimpleIT\Utils\Collection\CollectionInformation;

/**
 * Service which manages the test attempts
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class TestAttemptService extends TransactionalService implements TestAttemptServiceInterface
{
    /**
     * @var TestAttemptRepository
     */
    private $testAttemptRepository;

    /**
     * @var UserServiceInterface
     */
    private $userService;

    /**
     * @var AttemptService
     */
    private $attemptService;

    /**
     * @var  TestServiceInterface
     */
    private $testService;

    /**
     * Set testAttemptRepository
     *
     * @param $testAttemptRepository
     */
    public function setTestAttemptRepository($testAttemptRepository)
    {
        $this->testAttemptRepository = $testAttemptRepository;
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
     * Set attemptService
     *
     * @param AttemptService $attemptService
     */
    public function setAttemptService($attemptService)
    {
        $this->attemptService = $attemptService;
    }

    /**
     * Set testService
     *
     * @param TestServiceInterface $testService
     */
    public function setTestService($testService)
    {
        $this->testService = $testService;
    }

    /**
     * Find a test attempt by its id
     *
     * @param int $testAttemptId Test attempt Id
     *
     * @throws NonExistingObjectException
     * @return TestAttempt
     */
    public function get($testAttemptId)
    {
        $testAttempt = $this->testAttemptRepository->find($testAttemptId);
        if (is_null($testAttempt)) {
            throw new NonExistingObjectException();
        }

        return $testAttempt;
    }

    /**
     * Add a new test attempt to the database.
     *
     * @param int $testId
     * @param int $userId
     *
     * @return TestAttempt
     */
    public function add($testId, $userId)
    {
        $user = $this->userService->get($userId);
        $test = $this->testService->get($testId);
        $testAttempt = TestAttemptFactory::create($test, $user);

        /** @var TestAttempt $testAttempt */
        $this->em->persist($testAttempt);

        foreach ($test->getTestPositions() as $position) {
            /** @var TestPosition $position */
            $exerciseId = $position->getExercise()->getId();
            $this->attemptService->add(
                $exerciseId,
                $userId,
                $testAttempt->getId(),
                $position->getPosition()
            );
        }

        $this->em->flush();

        return $testAttempt;
    }

    /**
     * Get all the test attempts
     *
     * @param CollectionInformation $collectionInformation
     * @param int                   $userId
     * @param int                   $testId
     *
     * @return array
     */
    public function getAll(
        $collectionInformation = null,
        $userId = null,
        $testId = null
    )
    {
        $test = null;
        if (!is_null($testId)) {
            $test = $this->testService->get($testId);
        }

        return $this->testAttemptRepository->findAllBy(
            $collectionInformation,
            $userId,
            $test
        );
    }
}
