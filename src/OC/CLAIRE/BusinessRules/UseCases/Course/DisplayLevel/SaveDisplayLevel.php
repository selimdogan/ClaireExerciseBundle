<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\DisplayLevel;

use OC\CLAIRE\BusinessRules\Entities\Course\Course\CourseFactory;
use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseGateway;
use OC\CLAIRE\BusinessRules\Requestors\Course\DisplayLevel\SaveDisplayLevelRequest;
use OC\CLAIRE\BusinessRules\Requestors\UseCase;
use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;
use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class SaveDisplayLevel implements UseCase
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
        $course = $this->courseFactory->make();
        /** @var SaveDisplayLevelRequest $useCaseRequest */
        $course->setDisplayLevel($useCaseRequest->getDisplayLevel());
        $this->courseGateway->updateDraft($useCaseRequest->getCourseId(), $course);

        return null;
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
