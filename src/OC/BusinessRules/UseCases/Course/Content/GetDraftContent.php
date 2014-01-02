<?php

namespace OC\BusinessRules\UseCases\Course\Content;

use OC\BusinessRules\Gateways\Course\Course\CourseContentGateway;
use OC\BusinessRules\Requestors\Course\Content\GetDraftContentRequest;
use OC\BusinessRules\Requestors\UseCaseRequest;
use OC\BusinessRules\UseCases\Course\Content\DTO\GetContentResponseDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftContent extends GetContent
{
    /**
     * @var CourseContentGateway
     */
    private $CourseContentGateway;

    public function execute(UseCaseRequest $useCaseRequest)
    {
        /** @var GetDraftContentRequest $useCaseRequest */
        $content = $this->CourseContentGateway->findDraft(
            $useCaseRequest->getCourseId()
        );

        return new GetContentResponseDTO($content);
    }

    public function setCourseContentGateway(CourseContentGateway $CourseContentGateway)
    {
        $this->CourseContentGateway = $CourseContentGateway;
    }
}
