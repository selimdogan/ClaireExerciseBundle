<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\CourseDuration;

use OC\CLAIRE\BusinessRules\Entities\Course\Course\CourseFactory;
use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseGateway;
use OC\CLAIRE\BusinessRules\Requestors\Course\CourseDuration\SaveCourseDurationRequest;
use OC\CLAIRE\BusinessRules\Requestors\UseCase;
use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;
use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class SaveCourseDuration implements UseCase
{
    /**
     * @var CourseGateway
     */
    private $courseGateway;

    /**
     * @var CourseFactory
     */
    private $courseFactory;

    /**
     * @return UseCaseResponse
     */
    public function execute(UseCaseRequest $useCaseRequest)
    {
        /** @var SaveCourseDurationRequest $useCaseRequest */
        $course = $this->courseFactory->make();
        $course->setDuration($useCaseRequest->getDuration());
        $this->courseGateway->updateDraft($useCaseRequest->getCourseId(), $course);
    }

    public function setCourseGateway(CourseGateway $courseGateway)
    {
        $this->courseGateway = $courseGateway;
    }

    public function setCourseFactory(CourseFactory $courseFactory)
    {
        $this->courseFactory = $courseFactory;
    }
}
