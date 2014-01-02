<?php

namespace OC\BusinessRules\Entities\Course;

use SimpleIT\ApiResourcesBundle\Course\CourseResource;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class DraftCourseStub extends CourseStub
{
    const COURSE_STATUS = CourseResource::STATUS_DRAFT;

    public function getStatus()
    {
        return self::COURSE_STATUS;
    }
}
