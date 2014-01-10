<?php

namespace OC\CLAIRE\BusinessRules\Entities\Course;

use OC\CLAIRE\BusinessRules\Entities\Course\Course\Difficulty;
use OC\CLAIRE\BusinessRules\Entities\Course\Course\DisplayLevel;
use SimpleIT\ApiResourcesBundle\Course\CourseResource;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class BigHardDraftCourseStub extends CourseStub
{
    const COURSE_STATUS = CourseResource::STATUS_DRAFT;

    const DISPLAY_LEVEL = DisplayLevel::BIG;

    const DIFFICULTY = Difficulty::HARD;

    protected $status = self::COURSE_STATUS;

    protected $displayLevel = self::DISPLAY_LEVEL;

    protected $difficulty = self::DIFFICULTY;

}
