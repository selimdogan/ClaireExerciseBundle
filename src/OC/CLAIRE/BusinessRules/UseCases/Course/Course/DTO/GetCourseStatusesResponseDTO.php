<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Course\DTO;

use OC\CLAIRE\BusinessRules\Responders\Course\Course\GetCourseResponse;
use OC\CLAIRE\BusinessRules\Responders\Course\Course\GetCourseStatusesResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetCourseStatusesResponseDTO implements GetCourseStatusesResponse
{
    /**
     * @var GetCourseResponse[]
     */
    public $courses = array();

    /**
     * @return GetCourseResponse[]
     */
    public function getCourses()
    {
        return $this->courses;
    }
}
