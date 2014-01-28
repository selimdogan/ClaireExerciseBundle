<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\CourseDescription\DTO;

use OC\CLAIRE\BusinessRules\Requestors\Course\CourseDescription\SaveCourseDescriptionRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class SaveCourseDescriptionRequestDTO implements SaveCourseDescriptionRequest
{
    /**
     * @var int
     */
    public $courseId;

    /**
     * @var string
     */
    public $courseDescription;

    public function __construct($courseId, $courseDescription)
    {
        $this->courseId = $courseId;
        $this->courseDescription = $courseDescription;
    }

    /**
     * @return string
     */
    public function getCourseDescription()
    {
        return $this->courseDescription;
    }

    /**
     * @return int
     */
    public function getCourseId()
    {
        return $this->courseId;
    }
}
