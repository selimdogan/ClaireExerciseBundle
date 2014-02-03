<?php

namespace OC\CLAIRE\BusinessRules\Requestors\Course\CourseContent;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface GetDraftCourseContentRequest extends GetCourseContentRequest
{
    /**
     * @return int
     */
    public function getCourseId();
}
