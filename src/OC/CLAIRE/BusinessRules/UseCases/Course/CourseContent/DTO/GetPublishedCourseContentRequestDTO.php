<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\CourseContent\DTO;

use OC\CLAIRE\BusinessRules\Requestors\Course\CourseContent\GetPublishedCourseContentRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetPublishedCourseContentRequestDTO implements GetPublishedCourseContentRequest
{
    /**
     * @var int|string
     */
    private $courseIdentifier;

    public function __construct($courseIdentifier)
    {
        $this->courseIdentifier = $courseIdentifier;
    }

    /**
     * @return int|string
     */
    public function getCourseIdentifier()
    {
        return $this->courseIdentifier;
    }
}
