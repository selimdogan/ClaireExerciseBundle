<?php

namespace OC\CLAIRE\BusinessRules\Entities\Course;

use OC\CLAIRE\BusinessRules\Entities\Difficulty\Difficulty;
use SimpleIT\ApiResourcesBundle\Course\CourseResource;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class DraftCourseStub extends CourseStub
{
    const COURSE_STATUS = CourseResource::STATUS_DRAFT;

    const DIFFICULTY = Difficulty::EASY;

    public function getStatus()
    {
        return self::COURSE_STATUS;
    }

    public function getDifficulty()
    {
        return self::DIFFICULTY;
    }
}
