<?php

namespace OC\CLAIRE\BusinessRules\Entities\Course\Course;

use SimpleIT\ApiResourcesBundle\Course\CourseResource;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class MediumMediumDraftCourseStub extends CourseStub
{
    const COURSE_STATUS = CourseResource::STATUS_DRAFT;

    const DISPLAY_LEVEL = DisplayLevel::MEDIUM;

    const DIFFICULTY = Difficulty::MEDIUM;

    protected $status = self::COURSE_STATUS;

    protected $displayLevel = self::DISPLAY_LEVEL;

    protected $difficulty = self::DIFFICULTY;

}
