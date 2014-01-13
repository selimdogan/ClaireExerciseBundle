<?php

namespace OC\CLAIRE\BusinessRules\UseCases\AssociatedContent\CategoryByCourse;

use OC\CLAIRE\BusinessRules\Gateways\AssociatedContent\Category\CategoryByCourseGateway;
use OC\CLAIRE\BusinessRules\Requestors\AssociatedContent\CategoryByCourse\GetDraftCourseCategoryRequest;
use OC\CLAIRE\BusinessRules\Requestors\UseCase;
use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;
use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;
use
    OC\CLAIRE\BusinessRules\UseCases\AssociatedContent\CategoryByCourse\DTO\GetDraftCourseCategoryResponseDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftCourseCategory implements UseCase
{
    /**
     * @var CategoryByCourseGateway
     */
    private $categoryByCourseGateway;

    /**
     * @return UseCaseResponse
     */
    public function execute(UseCaseRequest $useCaseRequest)
    {
        /** @var GetDraftCourseCategoryRequest $useCaseRequest */
        $category = $this->categoryByCourseGateway->findDraft($useCaseRequest->getCourseId());

        return new GetDraftCourseCategoryResponseDTO($category->getId(), $category->getName());
    }

    public function setCategoryByCourseGateway(CategoryByCourseGateway $categoryByCourseGateway)
    {
        $this->categoryByCourseGateway = $categoryByCourseGateway;
    }
}
