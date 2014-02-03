<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\CourseToc\DTO;

use OC\CLAIRE\BusinessRules\Requestors\Course\CourseToc\GetWaitingForPublicationCourseTocRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetWaitingForPublicationCourseTocRequestDTO implements GetWaitingForPublicationCourseTocRequest
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
