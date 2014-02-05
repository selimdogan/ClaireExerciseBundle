<?php

namespace OC\CLAIRE\BusinessRules\Entities\Course\Course;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class DraftCourseStub extends CourseStub
{
    const COURSE_STATUS = Status::DRAFT;

    public function getStatus()
    {
        return self::COURSE_STATUS;
    }
}
