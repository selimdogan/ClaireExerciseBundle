<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\CourseContent\DTO;

use
    OC\CLAIRE\BusinessRules\Requestors\Course\CourseContent\GetWaitingForPublicationCourseContentRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetWaitingForPublicationCourseContentRequestDTO implements GetWaitingForPublicationCourseContentRequest
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
