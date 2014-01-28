<?php

namespace OC\CLAIRE\BusinessRules\Requestors\Course\CourseContent;

use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface SaveCourseContentRequest extends UseCaseRequest
{
    /**
     * @return int
     */
    public function getCourseId();

    /**
     * @return string
     */
    public function getContent();
}
