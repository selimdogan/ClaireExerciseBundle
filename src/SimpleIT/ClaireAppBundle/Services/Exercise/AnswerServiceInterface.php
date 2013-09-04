<?php

namespace SimpleIT\ClaireAppBundle\Services\Exercise;

use SimpleIT\ApiResourcesBundle\Exercise\ExerciseCreation\Common\LearnerAnswer;

/**
 * Interface for class AnswerService
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
interface AnswerServiceInterface
{
    /**
     * Add a new Learner Answer to an item
     *
     * @param int   $exerciseId
     * @param int   $itemNumber
     * @param array $la
     * @param array $options
     *
     * @internal param int $itemId
     * @return LearnerAnswer
     */
    public function add($exerciseId, $itemNumber, array $la, array $options);
}
