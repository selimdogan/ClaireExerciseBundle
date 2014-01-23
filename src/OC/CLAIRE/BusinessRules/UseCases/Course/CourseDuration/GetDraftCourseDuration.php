<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\CourseDuration;

use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseGateway;
use OC\CLAIRE\BusinessRules\Requestors\Course\CourseDuration\GetDraftCourseDurationRequest;
use OC\CLAIRE\BusinessRules\Requestors\UseCase;
use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;
use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;
use OC\CLAIRE\BusinessRules\UseCases\Course\CourseDuration\DTO\GetDraftCourseDurationResponseDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftCourseDuration implements UseCase
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
        /** @var GetDraftCourseDurationRequest $useCaseRequest */
        $course = $this->courseGateway->findDraft($useCaseRequest->getCourseId());

        if (null !== $duration = $course->getDuration()) {
            $duration = new \DateInterval($course->getDuration());
        }

        return new GetDraftCourseDurationResponseDTO($duration);
    }

    public function setCourseGateway(CourseGateway $courseGateway)
    {
        $this->courseGateway = $courseGateway;
    }
}
