<?php

namespace OC\CLAIRE\BusinessRules\UseCases\AssociatedContent\TagByCourse;

use
    OC\CLAIRE\BusinessRules\Requestors\AssociatedContent\TagByCourse\GetWaitingForPublicationCourseTagsRequest;
use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;
use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetWaitingForPublicationCourseTags extends GetCourseTags
{
    /**
     * @return UseCaseResponse
     */
    public function execute(UseCaseRequest $useCaseRequest)
    {
        /** @var GetWaitingForPublicationCourseTagsRequest $useCaseRequest */
        $this->tags = $this->tagByCourseGateway->findWaitingForPublication($useCaseRequest->getCourseId());

        return $this->buildResponse();
    }
}
