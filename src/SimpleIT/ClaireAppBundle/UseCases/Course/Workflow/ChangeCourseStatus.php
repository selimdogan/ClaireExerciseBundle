<?php

namespace SimpleIT\ClaireAppBundle\UseCases\Course\Workflow;

use SimpleIT\ClaireAppBundle\Gateways\Course\Course\CourseGateway;
use SimpleIT\ClaireAppBundle\Requestors\UseCase;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
abstract class ChangeCourseStatus implements UseCase
{
    /**
     * @var CourseGateway
     */
    protected $courseGateway;

    public function setCourseGateway(CourseGateway $courseGateway)
    {
        $this->courseGateway = $courseGateway;
    }
}
