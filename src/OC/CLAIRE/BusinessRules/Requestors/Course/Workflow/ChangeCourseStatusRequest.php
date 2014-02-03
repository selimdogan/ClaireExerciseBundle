<?php

namespace OC\CLAIRE\BusinessRules\Requestors\Course\Workflow;

use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;

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
