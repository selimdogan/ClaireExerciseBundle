<?php

namespace SimpleIT\ClaireAppBundle\UseCases\Course\Content;

use SimpleIT\ClaireAppBundle\Gateways\Course\Course\CourseContentGateway;
use SimpleIT\ClaireAppBundle\Requestors\UseCase;
use SimpleIT\ClaireAppBundle\Requestors\UseCaseRequest;
use SimpleIT\ClaireAppBundle\UseCases\Course\Content\DTO\SaveContentResponseDTO;

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
