<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\CourseDescription\DTO;

use OC\CLAIRE\BusinessRules\Requestors\Course\CourseDescription\GetDraftCourseDescriptionRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftCourseDescriptionRequestDTO implements GetDraftCourseDescriptionRequest
{
    /**
     * @var int
     */
    public $courseId;

    /**
     * @return int
     */
    public function getCourseId()
    {
        return $this->courseId;
    }
}
