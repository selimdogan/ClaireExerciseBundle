<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\CourseContent;

use OC\CLAIRE\BusinessRules\Requestors\Course\CourseContent\GetDraftCourseContentRequest;
use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;
use OC\CLAIRE\BusinessRules\UseCases\Course\CourseContent\DTO\GetCourseContentResponseDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftCourseContent extends GetCourseContent
{
    public function execute(UseCaseRequest $useCaseRequest)
    {
        /** @var GetDraftCourseContentRequest $useCaseRequest */
        $content = $this->CourseContentGateway->findDraft(
            $useCaseRequest->getCourseId()
        );

        return new GetCourseContentResponseDTO($content);
    }
}
