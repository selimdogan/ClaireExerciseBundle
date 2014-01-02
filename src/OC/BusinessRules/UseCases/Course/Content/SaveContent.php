<?php

namespace OC\BusinessRules\UseCases\Course\Content;

use OC\BusinessRules\Gateways\Course\Course\CourseContentGateway;
use OC\BusinessRules\Requestors\Course\Content\SaveContentRequest;
use OC\BusinessRules\Requestors\UseCase;
use OC\BusinessRules\Requestors\UseCaseRequest;
use OC\BusinessRules\UseCases\Course\Content\DTO\SaveContentResponseDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class SaveContent implements UseCase
{
    /**
     * @var CourseContentGateway
     */
    private $courseContentGateway;

    public function execute(UseCaseRequest $useCaseRequest)
    {
        /** @var SaveContentRequest $useCaseRequest */
        $content = $this->courseContentGateway->update(
            $useCaseRequest->getCourseId(),
            $useCaseRequest->getContent()
        );

        return new SaveContentResponseDTO($content);
    }

    public function setCourseContentGateway(CourseContentGateway $courseContentGateway)
    {
        $this->courseContentGateway = $courseContentGateway;
    }
}
