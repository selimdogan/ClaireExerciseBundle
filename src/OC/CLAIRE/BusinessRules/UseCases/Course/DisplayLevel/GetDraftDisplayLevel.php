<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\DisplayLevel;

use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseGateway;
use OC\CLAIRE\BusinessRules\Requestors\Course\DisplayLevel\GetDraftDisplayLevelRequest;
use OC\CLAIRE\BusinessRules\Requestors\UseCase;
use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;
use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;
use
    OC\CLAIRE\BusinessRules\UseCases\Course\DisplayLevel\DTO\GetDraftDisplayLevelResponseDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftDisplayLevel implements UseCase
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
        /** @var GetDraftDisplayLevelRequest $useCaseRequest */
        $course = $this->courseGateway->findDraft($useCaseRequest->getCourseId());

        return new GetDraftDisplayLevelResponseDTO($course->getDisplayLevel());
    }

    public function setCourseGateway(CourseGateway $courseGateway)
    {
        $this->courseGateway = $courseGateway;
    }

}
