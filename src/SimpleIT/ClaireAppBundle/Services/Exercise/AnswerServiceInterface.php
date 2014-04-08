<?php

namespace SimpleIT\ClaireAppBundle\Services\Exercise;

use SimpleIT\ApiResourcesBundle\Exercise\AnswerResource;

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
     * @param int   $attemptId
     * @param int   $itemId
     * @param array $answers
     *
     * @internal param int $itemId
     * @return AnswerResource
     */
    public function add($attemptId, $itemId, $answers);
}
