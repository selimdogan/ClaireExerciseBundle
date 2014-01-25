<?php

namespace OC\CLAIRE\BusinessRules\Responders\Course\CourseContent;

use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface SaveCourseContentResponse extends UseCaseResponse
{
    /**
     * @return string
     */
    public function getContent();
}
