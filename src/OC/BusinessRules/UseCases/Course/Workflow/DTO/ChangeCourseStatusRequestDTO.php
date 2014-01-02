<?php

namespace OC\BusinessRules\UseCases\Course\Workflow\DTO;

use OC\BusinessRules\Requestors\Course\Workflow\ChangeCourseStatusRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class ChangeCourseStatusRequestDTO implements ChangeCourseStatusRequest
{

    /**
     * @var int
     */
    protected $courseId;

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
