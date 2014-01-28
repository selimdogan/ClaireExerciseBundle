<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\CourseContent;

use
    OC\CLAIRE\BusinessRules\Requestors\Course\CourseContent\GetWaitingForPublicationCourseContentRequest;
use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;
use OC\CLAIRE\BusinessRules\UseCases\Course\CourseContent\DTO\GetCourseContentResponseDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetWaitingForPublicationCourseContent extends GetCourseContent
{
    public function execute(UseCaseRequest $useCaseRequest)
    {
        /** @var GetWaitingForPublicationCourseContentRequest $useCaseRequest */
        $content = $this->CourseContentGateway->findWaitingForPublication(
            $useCaseRequest->getCourseId()
        );

        return new GetCourseContentResponseDTO($content);
    }
}
