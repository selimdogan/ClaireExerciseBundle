<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\CourseDuration\DTO;

use OC\CLAIRE\BusinessRules\Requestors\Course\CourseDuration\SaveCourseDurationRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class SaveCourseDurationRequestDTO implements SaveCourseDurationRequest
{
    /**
     * @var int
     */
    public $courseId;

    /**
     * @var string
     */
    public $duration;

    public function __construct($courseId, $duration)
    {
        $this->courseId = $courseId;
        $this->duration = $duration;
    }

    /**
     * @return int
     */
    public function getCourseId()
    {
        return $this->courseId;
    }

    /**
     * @return \DateInterval
     */
    public function getDuration()
    {
        return $this->duration;
    }
}
