<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Course;

use OC\CLAIRE\BusinessRules\Requestors\Course\Course\GetWaitingForPublicationCourseRequest;
use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;
use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetWaitingForPublicationCourse extends GetCourse
{
    /**
     * @return UseCaseResponse
     */
    public function execute(UseCaseRequest $useCaseRequest)
    {
        /** @var GetWaitingForPublicationCourseRequest $useCaseRequest */
        $course = $this->courseGateway->findWaitingForPublication($useCaseRequest->getCourseId());

        return $this->buildCourseResponse($course);
    }

}
