<?php

namespace OC\BusinessRules\UseCases\Course\Course;

use OC\BusinessRules\Requestors\Course\Course\GetWaitingForPublicationCourseRequest;
use OC\BusinessRules\Requestors\UseCaseRequest;
use OC\BusinessRules\Responders\UseCaseResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetWaitingForPublicationCourse extends GetCourse
{
    /**
     * @return \OC\BusinessRules\Responders\UseCaseResponse
     */
    public function execute(UseCaseRequest $useCaseRequest)
    {
        /** @var GetWaitingForPublicationCourseRequest $useCaseRequest */
        $course = $this->courseGateway->findWaitingForPublication($useCaseRequest->getCourseId());

        return $this->buildResponse($course);
    }

}
