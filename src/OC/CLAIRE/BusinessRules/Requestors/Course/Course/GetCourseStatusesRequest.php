<?php

namespace OC\CLAIRE\BusinessRules\Requestors\Course\Course;

use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface GetCourseStatusesRequest extends UseCaseRequest
{
    /**
     * @return int|string
     */
    public function getCourseIdentifier();
}
