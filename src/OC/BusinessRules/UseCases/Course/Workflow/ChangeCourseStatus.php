<?php

namespace OC\BusinessRules\UseCases\Course\Workflow;

use OC\BusinessRules\Gateways\Course\Course\CourseGateway;
use OC\BusinessRules\Requestors\UseCase;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
abstract class ChangeCourseStatus implements UseCase
{
    /**
     * @var \OC\BusinessRules\Gateways\Course\Course\CourseGateway
     */
    protected $courseGateway;

    public function setCourseGateway(CourseGateway $courseGateway)
    {
        $this->courseGateway = $courseGateway;
    }
}
