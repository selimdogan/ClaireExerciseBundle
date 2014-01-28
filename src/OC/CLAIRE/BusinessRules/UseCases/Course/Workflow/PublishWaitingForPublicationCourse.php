<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Workflow;

use OC\CLAIRE\BusinessRules\Requestors\Course\Workflow\ChangeCourseStatusRequest;
use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;
use OC\CLAIRE\BusinessRules\UseCases\Course\Workflow\DTO\ChangeCourseStatusResponseDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class PublishWaitingForPublicationCourse extends ChangeCourseStatus
{
    public function execute(UseCaseRequest $useCaseRequest)
    {
        /** @var ChangeCourseStatusRequest $useCaseRequest */
        $this->courseGateway->updateWaitingForPublicationToPublished($useCaseRequest->getCourseId());

        return new ChangeCourseStatusResponseDTO();
    }

}
