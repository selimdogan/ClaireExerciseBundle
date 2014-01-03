<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Difficulty;

use OC\CLAIRE\BusinessRules\Gateways\Course\Difficulty\CourseDifficultyGateway;
use OC\CLAIRE\BusinessRules\Requestors\Course\Difficulty\GetCourseDifficultyRequest;
use OC\CLAIRE\BusinessRules\Requestors\UseCase;
use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;
use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;
use OC\CLAIRE\BusinessRules\UseCases\Course\Difficulty\DTO\GetDraftCourseDifficultyResponseDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftCourseDifficulty implements UseCase
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
        $difficulty = $this->courseDifficultyGateway->findDraft($useCaseRequest->getCourseId());
        $response = new GetDraftCourseDifficultyResponseDTO($difficulty);

        return $response;
    }

    public function setCourseDifficultyGateway(CourseDifficultyGateway $courseDifficultyGateway)
    {
        $this->courseDifficultyGateway = $courseDifficultyGateway;
    }
}
