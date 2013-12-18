<?php

namespace SimpleIT\ClaireAppBundle\UseCases\Course\Workflow;

use SimpleIT\ClaireAppBundle\Requestors\UseCaseRequest;
use SimpleIT\ClaireAppBundle\UseCases\Course\Workflow\DTO\ChangeCourseStatusResponseDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class ChangeCourseToPublished extends ChangeCourseStatus
{

    public function execute(UseCaseRequest $useCaseRequest)
    {
        /** ChangeCourseStatusRequest $useCaseRequest */
        $this->courseGateway->updateToPublished($useCaseRequest->getCourseId());

        return new ChangeCourseStatusResponseDTO();
    }

}
