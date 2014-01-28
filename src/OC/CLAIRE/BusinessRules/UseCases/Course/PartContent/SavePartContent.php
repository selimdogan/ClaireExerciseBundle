<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\PartContent;

use OC\CLAIRE\BusinessRules\Gateways\Course\Part\PartContentGateway;
use OC\CLAIRE\BusinessRules\Requestors\Course\PartContent\SavePartContentRequest;
use OC\CLAIRE\BusinessRules\Requestors\UseCase;
use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;
use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;
use OC\CLAIRE\BusinessRules\UseCases\Course\PartContent\DTO\SavePartContentResponseDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class SavePartContent implements UseCase
{
    /**
     * @var PartContentGateway
     */
    private $partContentGateway;

    /**
     * @return UseCaseResponse
     */
    public function execute(UseCaseRequest $useCaseRequest)
    {
        /** @var SavePartContentRequest $useCaseRequest */
        $content = $this->partContentGateway->update(
            $useCaseRequest->getCourseId(),
            $useCaseRequest->getPartId(),
            $useCaseRequest->getContent()
        );

        return new SavePartContentResponseDTO($content);
    }

    public function setPartContentGateway(PartContentGateway $partContentGateway)
    {
        $this->partContentGateway = $partContentGateway;
    }
}
