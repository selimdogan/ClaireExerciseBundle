<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\CourseToc\DTO;

use OC\CLAIRE\BusinessRules\Requestors\Course\CourseToc\GetPublishedCourseTocRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetPublishedCourseTocRequestDTO implements GetPublishedCourseTocRequest
{
    /**
     * @var int|string
     */
    public $courseIdentifier;

    public function __construct($courseIdentifier)
    {
        $this->courseIdentifier = $courseIdentifier;
    }
    /**
     * @return int
     */
    public function getCourseIdentifier()
    {
        return $this->courseIdentifier;
    }
}
