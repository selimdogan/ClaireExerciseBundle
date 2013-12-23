<?php

namespace SimpleIT\ClaireAppBundle\UseCases\Course\Content;

use SimpleIT\ClaireAppBundle\Gateways\Course\Course\CourseContentGateway;
use SimpleIT\ClaireAppBundle\Requestors\UseCaseRequest;
use SimpleIT\ClaireAppBundle\UseCases\Course\Content\DTO\GetContentResponseDTO;

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
