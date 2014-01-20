<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\CourseToc\DTO;

use OC\CLAIRE\BusinessRules\Requestors\Course\CourseToc\GetDraftCourseTocRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftCourseTocRequestDTO implements GetDraftCourseTocRequest
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
