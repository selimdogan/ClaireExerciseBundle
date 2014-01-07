<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Content;

use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseContentGateway;
use OC\CLAIRE\BusinessRules\Requestors\Course\Content\SaveContentRequest;
use OC\CLAIRE\BusinessRules\Requestors\UseCase;
use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;
use OC\CLAIRE\BusinessRules\UseCases\Course\Content\DTO\SaveContentResponseDTO;

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
