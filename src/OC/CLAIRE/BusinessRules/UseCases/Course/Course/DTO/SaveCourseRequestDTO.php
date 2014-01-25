<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Course\DTO;

use OC\CLAIRE\BusinessRules\Requestors\Course\Course\SaveCourseRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class SaveCourseRequestDTO implements SaveCourseRequest
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
