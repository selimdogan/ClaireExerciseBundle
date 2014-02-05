<?php

namespace OC\CLAIRE\BusinessRules\UseCases\AssociatedContent\CategoryByCourse;

use OC\CLAIRE\BusinessRules\Entities\Course\Course\Status;
use OC\CLAIRE\BusinessRules\Exceptions\AssociatedContent\Category\SaveCategoryByCourseException;
use OC\CLAIRE\BusinessRules\Gateways\AssociatedContent\Category\CategoryByCourseGateway;
use OC\CLAIRE\BusinessRules\Requestors\AssociatedContent\CategoryByCourse\SaveCourseCategoryRequest;
use OC\CLAIRE\BusinessRules\Requestors\UseCase;
use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;
use OC\CLAIRE\BusinessRules\Responders\Course\Course\GetCourseStatusesResponse;
use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;
use OC\CLAIRE\BusinessRules\UseCases\Course\Course\DTO\GetCourseStatusesRequestDTO;
use OC\CLAIRE\BusinessRules\UseCases\Course\Course\GetCourseStatuses;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class SaveCourseCategory implements UseCase
{
    /**
     * @var GetCourseStatuses
     */
    private $getCourseStatuses;

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
        $this->checkCourseCanChangeCategory($useCaseRequest->getCourseId());
        $this->categoryByCourseGateway->update(
            $useCaseRequest->getCategoryId(),
            $useCaseRequest->getCourseId()
        );
    }

    /**
     * @throws SaveCategoryByCourseException
     */
    private function checkCourseCanChangeCategory($courseId)
    {
        /** @var GetCourseStatusesResponse $response */
        $response = $this->getCourseStatuses->execute(
            new GetCourseStatusesRequestDTO($courseId)
        );

        $courses = $response->getCourses();
        if (isset($courses[Status::PUBLISHED])) {
            throw new SaveCategoryByCourseException();
        }
    }

    public function setGetCourseStatuses(GetCourseStatuses $getCourseStatuses)
    {
        $this->getCourseStatuses = $getCourseStatuses;
    }

    public function setCategoryByCourseGateway(CategoryByCourseGateway $categoryByCourseGateway)
    {
        $this->categoryByCourseGateway = $categoryByCourseGateway;
    }
}
