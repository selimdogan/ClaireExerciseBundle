<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\CourseContent;

use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseContentGateway;
use OC\CLAIRE\BusinessRules\Requestors\UseCase;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
abstract class GetCourseContent implements UseCase
{
    /**
     * @var CourseContentGateway
     */
    protected $CourseContentGateway;

    public function setCourseContentGateway(CourseContentGateway $CourseContentGateway)
    {
        $this->CourseContentGateway = $CourseContentGateway;
    }
}
