<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Course;

use OC\CLAIRE\BusinessRules\Requestors\Course\Course\GetDraftCourseRequest;
use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;
use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;

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
        /** @var GetDraftCourseRequest $useCaseRequest */
        $course = $this->courseGateway->findDraft($useCaseRequest->getCourseId());

        return $this->buildResponse($course);
    }

}
