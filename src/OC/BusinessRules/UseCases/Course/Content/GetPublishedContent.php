<?php

namespace OC\BusinessRules\UseCases\Course\Content;

use OC\BusinessRules\Gateways\Course\Course\CourseContentGateway;
use OC\BusinessRules\Requestors\Course\Content\GetPublishedContentRequest;
use OC\BusinessRules\Requestors\UseCaseRequest;
use OC\BusinessRules\UseCases\Course\Content\DTO\GetContentResponseDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetPublishedContent extends GetContent
{
    /**
     * @var CourseContentGateway
     */
    private $CourseContentGateway;

    public function execute(UseCaseRequest $useCaseRequest)
    {
        /** @var GetPublishedContentRequest $useCaseRequest */
        $content = $this->CourseContentGateway->findPublished(
            $useCaseRequest->getCourseIdentifier()
        );

        return new GetContentResponseDTO($content);
    }

    public function setCourseContentGateway(CourseContentGateway $CourseContentGateway)
    {
        $this->CourseContentGateway = $CourseContentGateway;
    }

}
