<?php

namespace OC\CLAIRE\BusinessRules\Entities\Course;

use SimpleIT\ApiResourcesBundle\Course\CourseResource;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class PublishedCourseStub extends CourseStub
{
    const COURSE_STATUS = CourseResource::STATUS_PUBLISHED;

    public function getStatus()
    {
        return self::COURSE_STATUS;
    }
}
