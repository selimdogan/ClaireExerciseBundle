<?php

namespace OC\CLAIRE\BusinessRules\Requestors\Course\CourseDescription;

use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface SaveCourseDescriptionRequest extends UseCaseRequest
{
    /**
     * @return string
     */
    public function getCourseDescription();

    /**
     * @return int
     */
    public function getCourseId();
}
