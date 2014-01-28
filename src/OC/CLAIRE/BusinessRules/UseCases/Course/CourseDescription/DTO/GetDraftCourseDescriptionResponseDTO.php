<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\CourseDescription\DTO;

use OC\CLAIRE\BusinessRules\Responders\Course\CourseDescription\GetDraftCourseDescriptionResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftCourseDescriptionResponseDTO implements GetDraftCourseDescriptionResponse
{
    /**
     * @var string
     */
    public $courseDescription;

    public function __construct($courseDescription)
    {
        $this->courseDescription = $courseDescription;
    }

    /**
     * @return string
     */
    public function getCourseDescription()
    {
        return $this->courseDescription;
    }
}
