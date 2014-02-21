<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Workflow\DTO;

use OC\CLAIRE\BusinessRules\Requestors\Course\Workflow\DismissWaitingForPublicationCourseRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class DismissWaitingForPublicationCourseRequestDTO implements DismissWaitingForPublicationCourseRequest
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
