<?php

namespace SimpleIT\ClaireAppBundle\Entities\Course;

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
