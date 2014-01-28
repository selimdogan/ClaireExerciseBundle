<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\PartContent;

use
    OC\CLAIRE\BusinessRules\Requestors\Course\PartContent\GetWaitingForPublicationPartContentRequest;
use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;
use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;
use OC\CLAIRE\BusinessRules\UseCases\Course\PartContent\DTO\GetPartContentResponseDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetWaitingForPublicationPartContent extends GetPartContent
{
    /**
     * @return UseCaseResponse
     */
    public function execute(UseCaseRequest $useCaseRequest)
    {
        /** @var GetWaitingForPublicationPartContentRequest $useCaseRequest */
        $content = $this->partContentGateway->findWaitingForPublication(
            $useCaseRequest->getCourseId(),
            $useCaseRequest->getPartId()
        );

        return new GetPartContentResponseDTO($content);
    }
}
