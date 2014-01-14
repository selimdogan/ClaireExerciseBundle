<?php

namespace OC\CLAIRE\BusinessRules\UseCases\AssociatedContent\CategoryByCourse;

use
    OC\CLAIRE\BusinessRules\Requestors\AssociatedContent\CategoryByCourse\GetDraftCourseCategoryRequest;
use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;
use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;
use
    OC\CLAIRE\BusinessRules\UseCases\AssociatedContent\CategoryByCourse\DTO\GetDraftCourseCategoryResponseDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftCourseCategory extends GetCourseCategory
{

    /**
     * @return UseCaseResponse
     */
    public function execute(UseCaseRequest $useCaseRequest)
    {
        /** @var GetDraftCourseCategoryRequest $useCaseRequest */
        $category = $this->categoryByCourseGateway->findDraft($useCaseRequest->getCourseId());

        return new GetDraftCourseCategoryResponseDTO($category->getId(), $category->getName());
    }
}
