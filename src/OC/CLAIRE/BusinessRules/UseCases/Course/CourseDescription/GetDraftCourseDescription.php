<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\CourseDescription;

use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseGateway;
use OC\CLAIRE\BusinessRules\Requestors\Course\CourseDescription\GetDraftCourseDescriptionRequest;
use OC\CLAIRE\BusinessRules\Requestors\UseCase;
use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;
use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;
use
    OC\CLAIRE\BusinessRules\UseCases\Course\CourseDescription\DTO\GetDraftCourseDescriptionResponseDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftCourseDescription implements UseCase
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
        /** @var GetDraftCourseDescriptionRequest $useCaseRequest */
        $course = $this->courseGateway->findDraft($useCaseRequest->getCourseId());

        return new GetDraftCourseDescriptionResponseDTO($course->getDescription());
    }

    public function setCourseGateway(CourseGateway $courseGateway)
    {
        $this->courseGateway = $courseGateway;
    }
}
