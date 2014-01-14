<?php

namespace OC\CLAIRE\BusinessRules\Entities\Course;

use OC\CLAIRE\BusinessRules\Entities\Course\Course\Difficulty;
use OC\CLAIRE\BusinessRules\Entities\Course\Course\DisplayLevel;
use SimpleIT\ApiResourcesBundle\Course\CourseResource;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class SmallEasyDraftCourseStub extends CourseStub
{
    const COURSE_STATUS = CourseResource::STATUS_DRAFT;

    const DISPLAY_LEVEL = DisplayLevel::SMALL;

    const DIFFICULTY = Difficulty::EASY;

    const DESCRIPTION = 'Course description';

    protected $status = self::COURSE_STATUS;

    protected $displayLevel = self::DISPLAY_LEVEL;

    protected $difficulty = self::DIFFICULTY;

    protected $description = self::DESCRIPTION;

}
