<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Difficulty;

use OC\CLAIRE\BusinessRules\Gateways\Course\Difficulty\CourseDifficultyGateway;
use OC\CLAIRE\BusinessRules\Requestors\Course\Difficulty\SaveCourseDifficultyRequest;
use OC\CLAIRE\BusinessRules\Requestors\UseCase;
use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;
use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class SaveCourseDifficulty implements UseCase
{
    /**
     * @var CourseDifficultyGateway
     */
    private $courseDifficultyGateway;

    /**
     * @return UseCaseResponse
     */
    public function execute(UseCaseRequest $useCaseRequest)
    {
        /** @var SaveCourseDifficultyRequest $useCaseRequest */
        $this->courseDifficultyGateway->update(
            $useCaseRequest->getCourseId(),
            $useCaseRequest->getDifficulty()
        );
    }

    public function setCourseDifficultyGateway(CourseDifficultyGateway $courseDifficultyGateway)
    {
        $this->courseDifficultyGateway = $courseDifficultyGateway;
    }
}
