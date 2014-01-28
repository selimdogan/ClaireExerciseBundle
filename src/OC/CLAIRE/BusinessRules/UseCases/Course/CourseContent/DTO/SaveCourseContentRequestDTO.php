<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\CourseContent\DTO;

use OC\CLAIRE\BusinessRules\Requestors\Course\CourseContent\SaveCourseContentRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class SaveCourseContentRequestDTO implements SaveCourseContentRequest
{
    /**
     * @var int
     */
    public $courseId;

    /**
     * @var string
     */
    public $content;

    public function __construct($courseId, $content)
    {
        $this->courseId = $courseId;
        $this->content = $content;
    }

    /**
     * @return int
     */
    public function getCourseId()
    {
        return $this->courseId;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
}
