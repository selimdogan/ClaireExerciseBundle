<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\CourseContent\DTO;

use OC\CLAIRE\BusinessRules\Responders\Course\CourseContent\SaveCourseContentResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class SaveCourseContentResponseDTO implements SaveCourseContentResponse
{
    /**
     * @var string
     */
    public $content;

    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

}
