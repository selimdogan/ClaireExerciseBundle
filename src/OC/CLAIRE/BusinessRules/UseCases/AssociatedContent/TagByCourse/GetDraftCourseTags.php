<?php

namespace OC\CLAIRE\BusinessRules\UseCases\AssociatedContent\TagByCourse;

use OC\CLAIRE\BusinessRules\Requestors\AssociatedContent\TagByCourse\GetDraftCourseTagsRequest;
use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;
use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftCourseTags extends GetCourseTags
{
    /**
     * @return UseCaseResponse
     */
    public function execute(UseCaseRequest $useCaseRequest)
    {
        /** @var GetDraftCourseTagsRequest $useCaseRequest */
        $this->tags = $this->tagByCourseGateway->findDraft($useCaseRequest->getCourseId());

        return $this->buildResponse();
    }
}
