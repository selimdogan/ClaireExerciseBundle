<?php

namespace OC\CLAIRE\BusinessRules\Requestors\Course\CourseContent;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface GetPublishedCourseContentRequest extends GetCourseContentRequest
{
    /**
     * @return int|string
     */
    public function getCourseIdentifier();
}
