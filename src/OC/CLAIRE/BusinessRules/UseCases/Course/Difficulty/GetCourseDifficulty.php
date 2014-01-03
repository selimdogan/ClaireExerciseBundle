<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Difficulty;

use OC\CLAIRE\BusinessRules\Gateways\Course\Difficulty\CourseDifficultyGateway;
use OC\CLAIRE\BusinessRules\Requestors\Course\Difficulty\GetCourseDifficultyRequest;
use OC\CLAIRE\BusinessRules\Requestors\UseCase;
use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;
use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;
use OC\CLAIRE\BusinessRules\UseCases\Course\Difficulty\DTO\GetCourseDifficultyResponseDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetCourseDifficulty implements UseCase
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
        /** @var GetCourseDifficultyRequest $useCaseRequest */
        $difficulty = $this->courseDifficultyGateway->find($useCaseRequest->getCourseId());
        $response = new GetCourseDifficultyResponseDTO($difficulty);

        return $response;
    }

    public function setCourseDifficultyGateway(CourseDifficultyGateway $courseDifficultyGateway)
    {
        $this->courseDifficultyGateway = $courseDifficultyGateway;
    }
}
