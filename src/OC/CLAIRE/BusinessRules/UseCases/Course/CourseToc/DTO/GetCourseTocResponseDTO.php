<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\CourseToc\DTO;

use OC\CLAIRE\BusinessRules\Responders\Course\CourseToc\GetCourseTocResponse;
use SimpleIT\ApiResourcesBundle\Course\CourseResource;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetCourseTocResponseDTO implements GetCourseTocResponse
{
    /**
     * @var CourseResource
     */
    public $courseToc;

    public function __construct($courseToc)
    {
        $this->courseToc = $courseToc;
    }

    /**
     * @return CourseResource
     */
    public function getCourseToc()
    {
        return $this->courseToc;
    }
}
