<?php

namespace OC\BusinessRules\UseCases\Course\Workflow;

use OC\BusinessRules\Requestors\UseCaseRequest;
use OC\BusinessRules\UseCases\Course\Workflow\DTO\ChangeCourseStatusResponseDTO;

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
