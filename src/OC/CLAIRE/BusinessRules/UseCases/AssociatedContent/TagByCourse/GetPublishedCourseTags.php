<?php

namespace OC\CLAIRE\BusinessRules\UseCases\AssociatedContent\TagByCourse;

use OC\CLAIRE\BusinessRules\Requestors\AssociatedContent\TagByCourse\GetPublishedCourseTagsRequest;
use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;
use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetPublishedCourseTags extends GetCourseTags
{
    /**
     * @return UseCaseResponse
     */
    public function execute(UseCaseRequest $useCaseRequest)
    {
        /** @var GetPublishedCourseTagsRequest $useCaseRequest */
        $this->tags = $this->tagByCourseGateway->findPublished($useCaseRequest->getCourseIdentifier());

        return $this->buildResponse();
    }
}
