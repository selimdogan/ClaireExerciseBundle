<?php

namespace SimpleIT\ClaireExerciseBundle\Service\Exercise\CreatedExercise;

use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Entity\CreatedExercise\StoredExercise;
use SimpleIT\Utils\Collection\CollectionInformation;


/**
 * Interface for service which manages the stored exercises
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
interface StoredExerciseServiceInterface
{
    /**
     * Find a storedExercise by its id
     *
     * @param int $storedExerciseId Stored Exercise Id
     *
     * @throws NonExistingObjectException
     * @return StoredExercise
     */
    public function get($storedExerciseId);

    /**
     * Get all the stored exercises corresponding to an exercise model (if specified)
     *
     * @param CollectionInformation $collectionInformation
     * @param int                   $exerciseModelId
     *
     * @return array
     */
    public function getAll(
        $collectionInformation = null,
        $exerciseModelId = null
    );

    /**
     * Get all by test attempt id
     *
     * @param $testAttemptId
     *
     * @return array
     */
    public function getAllByTestAttempt($testAttemptId);

    /**
     * Add a new exercise model by owner exercise model id
     *
     * @param $oemId
     *
     * @return StoredExercise
     */
    public function addByExerciseModel($oemId);
}
