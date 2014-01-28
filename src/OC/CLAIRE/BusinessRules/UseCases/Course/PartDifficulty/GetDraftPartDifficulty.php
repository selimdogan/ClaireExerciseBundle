<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\PartDifficulty;

use OC\CLAIRE\BusinessRules\Gateways\Course\Part\PartGateway;
use OC\CLAIRE\BusinessRules\Requestors\Course\PartDifficulty\GetDraftPartDifficultyRequest;
use OC\CLAIRE\BusinessRules\Requestors\UseCase;
use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;
use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;
use OC\CLAIRE\BusinessRules\UseCases\Course\PartDifficulty\DTO\GetDraftPartDifficultyResponseDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftPartDifficulty implements UseCase
{
    /**
     * @var PartGateway
     */
    private $partGateway;

    /**
     * @return UseCaseResponse
     */
    public function execute(UseCaseRequest $useCaseRequest)
    {
        /** @var GetDraftPartDifficultyRequest $useCaseRequest */
        $part = $this->partGateway->findDraft($useCaseRequest->getCourseId(), $useCaseRequest->getPartId());
        $response = new GetDraftPartDifficultyResponseDTO($part->getDifficulty());

        return $response;
    }

    public function setPartGateway(PartGateway $partGateway)
    {
        $this->partGateway = $partGateway;
    }
}
