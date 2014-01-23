<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\CourseDuration\DTO;

use OC\CLAIRE\BusinessRules\Requestors\Course\CourseDuration\GetDraftCourseDurationRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftCourseDurationRequestDTO implements GetDraftCourseDurationRequest
{
    /**
     * @var int
     */
    public $courseId;

    public function __construct($courseId)
    {
        $this->courseId = $courseId;
    }

    /**
     * @return int
     */
    public function getCourseId()
    {
        return $this->courseId;
    }
}
