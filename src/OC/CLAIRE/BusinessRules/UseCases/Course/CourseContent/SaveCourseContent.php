<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\CourseContent;

use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseContentGateway;
use OC\CLAIRE\BusinessRules\Requestors\Course\CourseContent\SaveCourseContentRequest;
use OC\CLAIRE\BusinessRules\Requestors\UseCase;
use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;
use OC\CLAIRE\BusinessRules\UseCases\Course\CourseContent\DTO\SaveCourseContentResponseDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class SaveCourseContent implements UseCase
{
    /**
     * @var CourseContentGateway
     */
    private $courseContentGateway;

    public function execute(UseCaseRequest $useCaseRequest)
    {
        /** @var SaveCourseContentRequest $useCaseRequest */
        $content = $this->courseContentGateway->update(
            $useCaseRequest->getCourseId(),
            $useCaseRequest->getContent()
        );

        return new SaveCourseContentResponseDTO($content);
    }

    public function setCourseContentGateway(CourseContentGateway $courseContentGateway)
    {
        $this->courseContentGateway = $courseContentGateway;
    }
}
