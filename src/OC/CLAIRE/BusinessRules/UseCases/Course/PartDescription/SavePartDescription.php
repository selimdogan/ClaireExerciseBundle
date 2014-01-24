<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\PartDescription;

use OC\CLAIRE\BusinessRules\Entities\Course\Part\PartFactory;
use OC\CLAIRE\BusinessRules\Gateways\Course\Part\PartGateway;
use OC\CLAIRE\BusinessRules\Requestors\Course\PartDescription\SavePartDescriptionRequest;
use OC\CLAIRE\BusinessRules\Requestors\UseCase;
use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;
use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class SavePartDescription implements UseCase
{
    /**
     * @var PartGateway
     */
    private $partGateway;

    /**
     * @var PartFactory
     */
    private $partFactory;

    /**
     * @return UseCaseResponse
     */
    public function execute(UseCaseRequest $useCaseRequest)
    {
        $part = $this->partFactory->make();
        /** @var SavePartDescriptionRequest $useCaseRequest */
        $part->setDescription($useCaseRequest->getDescription());
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

    public function setPartFactory(PartFactory $partFactory)
    {
        $this->partFactory = $partFactory;
    }
}
