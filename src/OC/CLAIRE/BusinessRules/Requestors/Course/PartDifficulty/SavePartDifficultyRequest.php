<?php

namespace OC\CLAIRE\BusinessRules\Requestors\Course\PartDifficulty;

use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface SavePartDifficultyRequest extends UseCaseRequest
{
    /**
     * @return int
     */
    public function getCourseId();

    /**
     * @return string
     */
    public function getDifficulty();

    /**
     * @return int
     */
    public function getPartId();

}
