<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\CourseContent;

use OC\CLAIRE\BusinessRules\Requestors\Course\CourseContent\GetPublishedCourseContentRequest;
use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;
use OC\CLAIRE\BusinessRules\UseCases\Course\CourseContent\DTO\GetCourseContentResponseDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetPublishedCourseContent extends GetCourseContent
{
    public function execute(UseCaseRequest $useCaseRequest)
    {
        /** @var GetPublishedCourseContentRequest $useCaseRequest */
        $content = $this->CourseContentGateway->findPublished(
            $useCaseRequest->getCourseIdentifier()
        );

        return new GetCourseContentResponseDTO($content);
    }
}
