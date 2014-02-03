<?php

namespace OC\CLAIRE\BusinessRules\Entities\Course\Course;

use SimpleIT\ApiResourcesBundle\Course\CourseResource;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class SmallEasyDraftCourseStub extends CourseStub
{
    const COURSE_STATUS = CourseResource::STATUS_DRAFT;

    const DISPLAY_LEVEL = DisplayLevel::SMALL;

    const DIFFICULTY = Difficulty::EASY;

    const DURATION = 'P1D';

    protected $status = self::COURSE_STATUS;

    protected $displayLevel = self::DISPLAY_LEVEL;

    protected $difficulty = self::DIFFICULTY;

    protected $duration = self::DURATION;

}