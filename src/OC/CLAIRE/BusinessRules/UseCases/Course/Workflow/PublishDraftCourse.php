<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Workflow;

use OC\CLAIRE\BusinessRules\Requestors\Course\Workflow\ChangeCourseStatusRequest;
use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class PublishDraftCourse extends ChangeCourseStatus
{
    public function execute(UseCaseRequest $useCaseRequest)
    {
        /** @var ChangeCourseStatusRequest $useCaseRequest */
        $this->courseGateway->updateDraftToPublished($useCaseRequest->getCourseId());
    }
}
