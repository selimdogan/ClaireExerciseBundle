<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Workflow;

use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseGateway;
use OC\CLAIRE\BusinessRules\Requestors\Course\Workflow\DismissWaitingForPublicationCourseRequest;
use OC\CLAIRE\BusinessRules\Requestors\UseCase;
use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;
use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class DismissWaitingForPublicationCourse implements UseCase
{
    /**
     * @var CourseGateway
     */
    private $courseGateway;

    /**
     * @return UseCaseResponse
     */
    public function execute(UseCaseRequest $useCaseRequest)
    {
        /** @var DismissWaitingForPublicationCourseRequest $useCaseRequest */
        $this->courseGateway->deleteWaitingForPublication($useCaseRequest->getCourseId());
    }

    public function setCourseGateway(CourseGateway $courseGateway)
    {
        $this->courseGateway = $courseGateway;
    }
}
