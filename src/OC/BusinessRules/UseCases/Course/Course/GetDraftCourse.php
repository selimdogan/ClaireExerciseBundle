<?php

namespace OC\BusinessRules\UseCases\Course\Course;

use OC\BusinessRules\Requestors\Course\Course\GetDraftCourseRequest;
use OC\BusinessRules\Requestors\UseCaseRequest;
use OC\BusinessRules\Responders\UseCaseResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftCourse extends GetCourse
{
    /**
     * @return UseCaseResponse
     */
    public function execute(UseCaseRequest $useCaseRequest)
    {
        /** @var \OC\BusinessRules\Requestors\Course\Course\GetDraftCourseRequest $useCaseRequest */
        $course = $this->courseGateway->findDraft($useCaseRequest->getCourseId());

        return $this->buildResponse($course);
    }

}
