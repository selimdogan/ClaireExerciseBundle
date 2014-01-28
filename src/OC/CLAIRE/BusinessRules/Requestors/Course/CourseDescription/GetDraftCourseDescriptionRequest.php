<?php

namespace OC\CLAIRE\BusinessRules\Requestors\Course\CourseDescription;

use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface GetDraftCourseDescriptionRequest extends UseCaseRequest
{
    /**
     * @return int
     */
    public function getCourseId();
}
