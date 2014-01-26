<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Course;

use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseGateway;
use OC\CLAIRE\BusinessRules\Requestors\Course\Course\SaveCourseRequest;
use OC\CLAIRE\BusinessRules\Requestors\UseCase;
use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;
use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;
use SimpleIT\ClaireAppBundle\Entity\Course\Course\CourseBuilderImpl;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class SaveCourse implements UseCase
{
    /**
     * @var CourseGateway
     */
    private $courseGateway;

    /**
     * @return UseCaseResponse
     */
    public function execute(UseCaseRequest $useCaseRequest)
    {
        /** @var SaveCourseRequest $useCaseRequest */
        $course = CourseBuilderImpl::create()
            ->withDescription($useCaseRequest->getCourseDescription())
            ->withDifficulty($useCaseRequest->getCourseDifficulty())
            ->withDisplayLevel($useCaseRequest->getCourseDisplayLevel())
            ->withDuration($useCaseRequest->getCourseDuration())
            ->withImage($useCaseRequest->getCourseImage())
            ->withTitle($useCaseRequest->getCourseTitle())
            ->build();
        $this->courseGateway->updateDraft($useCaseRequest->getCourseId(), $course);
    }

    public function setCourseGateway(CourseGateway $courseGateway)
    {
        $this->courseGateway = $courseGateway;
    }
}
