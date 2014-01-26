<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Part;

use OC\CLAIRE\BusinessRules\Gateways\Course\Part\PartGateway;
use OC\CLAIRE\BusinessRules\Requestors\Course\Part\SavePartRequest;
use OC\CLAIRE\BusinessRules\Requestors\UseCase;
use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;
use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;
use SimpleIT\ClaireAppBundle\Entity\Course\Part\PartBuilderImpl;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class SavePart implements UseCase
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
        /** @var SavePartRequest $useCaseRequest */
        $part =
            PartBuilderImpl::create()
                ->withDescription($useCaseRequest->getPartDescription())
                ->withDifficulty($useCaseRequest->getPartDifficulty())
                ->withDuration($useCaseRequest->getPartDuration())
                ->withTitle($useCaseRequest->getPartTitle())
                ->build();
        $this->partGateway->updateDraft(
            $useCaseRequest->getCourseId(),
            $useCaseRequest->getPartId(),
            $part
        );
    }

    public function setPartGateway(PartGateway $partGateway)
    {
        $this->partGateway = $partGateway;
    }
}
