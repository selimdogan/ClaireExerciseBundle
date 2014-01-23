<?php

namespace OC\CLAIRE\BusinessRules\Requestors\Course\CourseDuration;

use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface GetDraftCourseDurationRequest extends UseCaseRequest
{
    /**
     * @return int
     */
    public function getCourseId();
} 
