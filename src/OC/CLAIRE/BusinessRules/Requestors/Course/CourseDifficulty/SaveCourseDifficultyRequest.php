<?php

namespace OC\CLAIRE\BusinessRules\Requestors\Course\CourseDifficulty;

use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface SaveCourseDifficultyRequest extends UseCaseRequest
{
    /**
     * @return int
     */
    public function getCourseId();

    /**
     * @return string
     */
    public function getDifficulty();
}
