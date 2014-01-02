<?php

namespace OC\BusinessRules\Requestors\Course\Workflow;

use OC\BusinessRules\Requestors\UseCaseRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface ChangeCourseStatusRequest extends UseCaseRequest
{
    /**
     * @return int
     */
    public function getCourseId();
}
