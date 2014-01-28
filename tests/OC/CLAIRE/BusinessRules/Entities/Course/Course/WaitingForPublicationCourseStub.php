<?php

namespace OC\CLAIRE\BusinessRules\Entities\Course\Course;

use SimpleIT\ApiResourcesBundle\Course\CourseResource;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class WaitingForPublicationCourseStub extends CourseStub
{
    const COURSE_STATUS = CourseResource::STATUS_WAITING_FOR_PUBLICATION;

    public function getStatus()
    {
        return self::COURSE_STATUS;
    }
}
