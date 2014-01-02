<?php

namespace OC\BusinessRules\UseCases\Course\Course;

use OC\BusinessRules\Requestors\Course\Course\GetPublishedCourseRequest;
use OC\BusinessRules\Requestors\UseCaseRequest;
use OC\BusinessRules\Responders\UseCaseResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetPublishedCourse extends GetCourse
{
    /**
     * @return UseCaseResponse
     */
    public function execute(UseCaseRequest $useCaseRequest)
    {
        /** @var GetPublishedCourseRequest $useCaseRequest */
        $course = $this->courseGateway->findPublished($useCaseRequest->getCourseIdentifier());

        return $this->buildResponse($course);
    }
}
