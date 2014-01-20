<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\CourseToc\DTO;

use OC\CLAIRE\BusinessRules\Responders\Course\CourseToc\GetCourseTocResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetCourseTocResponseDTO implements GetCourseTocResponse
{
    public $courseToc;

    public function __construct($courseToc)
    {
        $this->courseToc = $courseToc;
    }

    /**
     * @return mixed
     */
    public function getCourseToc()
    {
        return $this->courseToc;
    }
}
