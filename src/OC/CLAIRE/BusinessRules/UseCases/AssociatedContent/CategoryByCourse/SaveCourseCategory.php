<?php

namespace OC\CLAIRE\BusinessRules\UseCases\AssociatedContent\CategoryByCourse;

use OC\CLAIRE\BusinessRules\Gateways\AssociatedContent\Category\CategoryByCourseGateway;
use OC\CLAIRE\BusinessRules\Requestors\AssociatedContent\CategoryByCourse\SaveCourseCategoryRequest;
use OC\CLAIRE\BusinessRules\Requestors\UseCase;
use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;
use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class SaveCourseCategory implements UseCase
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
        /** @var SaveCourseCategoryRequest $useCaseRequest */
        $this->categoryByCourseGateway->update(
            $useCaseRequest->getCategoryId(),
            $useCaseRequest->getCourseId()
        );
    }

    public function setCategoryByCourseGateway(CategoryByCourseGateway $categoryByCourseGateway)
    {
        $this->categoryByCourseGateway = $categoryByCourseGateway;
    }
}
