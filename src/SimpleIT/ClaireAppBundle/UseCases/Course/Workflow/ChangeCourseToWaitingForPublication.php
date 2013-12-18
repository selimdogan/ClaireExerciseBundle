<?php

namespace SimpleIT\ClaireAppBundle\UseCases\Course\Workflow;

use SimpleIT\ClaireAppBundle\Requestors\UseCaseRequest;
use SimpleIT\ClaireAppBundle\UseCases\Course\Workflow\DTO\ChangeCourseStatusResponseDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class ChangeCourseToWaitingForPublication extends ChangeCourseStatus
{
    public function execute(UseCaseRequest $useCaseRequest)
    {
        /** ChangeCourseStatusRequest $useCaseRequest */
        $this->courseGateway->updateToWaitingForPublication($useCaseRequest->getCourseId());

        return new ChangeCourseStatusResponseDTO();
    }
}
