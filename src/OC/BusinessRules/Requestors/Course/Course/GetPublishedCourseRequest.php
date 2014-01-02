<?php

namespace OC\BusinessRules\Requestors\Course\Course;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface GetPublishedCourseRequest extends GetCourseRequest
{
    /**
     * @return int|string
     */
    public function getCourseIdentifier();
}
