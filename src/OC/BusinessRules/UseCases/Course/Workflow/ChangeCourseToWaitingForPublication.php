<?php

namespace OC\BusinessRules\UseCases\Course\Workflow;

use OC\BusinessRules\Requestors\UseCaseRequest;
use OC\BusinessRules\UseCases\Course\Workflow\DTO\ChangeCourseStatusResponseDTO;

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
