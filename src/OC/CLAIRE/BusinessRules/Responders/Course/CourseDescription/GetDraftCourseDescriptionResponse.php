<?php

namespace OC\CLAIRE\BusinessRules\Responders\Course\CourseDescription;

use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface GetDraftCourseDescriptionResponse extends UseCaseResponse
{
    /**
     * @return string
     */
    public function getCourseDescription();
}
