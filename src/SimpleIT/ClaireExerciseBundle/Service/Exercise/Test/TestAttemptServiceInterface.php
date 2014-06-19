<?php

namespace SimpleIT\ClaireExerciseBundle\Service\Exercise\Test;

use SimpleIT\ClaireExerciseBundle\Entity\Test\TestAttempt;
use SimpleIT\Utils\Collection\CollectionInformation;

/**
 * Service which manages the test attempts
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
interface TestAttemptServiceInterface
{
    /**
     * Find a test attempt by its id
     *
     * @param int $testAttemptId Test attempt Id
     * @param int $userId
     *
     * @return TestAttempt
     */
    public function get($testAttemptId, $userId = null);

    /**
     * Add a new test attempt to the database.
     *
     * @param int $testId
     * @param int $userId
     *
     * @return TestAttempt
     */
    public function add($testId, $userId);

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
    );
}
