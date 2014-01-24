<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\PartDescription;

use OC\CLAIRE\BusinessRules\Gateways\Course\Part\PartGateway;
use OC\CLAIRE\BusinessRules\Requestors\Course\PartDescription\GetDraftPartDescriptionRequest;
use OC\CLAIRE\BusinessRules\Requestors\UseCase;
use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;
use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;
use OC\CLAIRE\BusinessRules\UseCases\Course\PartDescription\DTO\GetDraftPartDescriptionResponseDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftPartDescription implements UseCase
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
        /** @var GetDraftPartDescriptionRequest $useCaseRequest */
        $part = $this->partGateway->findDraft(
            $useCaseRequest->getCourseId(),
            $useCaseRequest->getPartId()
        );

        return new GetDraftPartDescriptionResponseDTO($part->getDescription());
    }

    public function setPartGateway(PartGateway $partGateway)
    {
        $this->partGateway = $partGateway;
    }
}
