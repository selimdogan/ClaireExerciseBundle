<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\PartContent;

use OC\CLAIRE\BusinessRules\Requestors\Course\PartContent\GetDraftPartContentRequest;
use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;
use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;
use OC\CLAIRE\BusinessRules\UseCases\Course\PartContent\DTO\GetPartContentResponseDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftPartContent extends GetPartContent
{
    /**
     * @return UseCaseResponse
     */
    public function execute(UseCaseRequest $useCaseRequest)
    {
        /** @var GetDraftPartContentRequest $useCaseRequest */

        return new GetPartContentResponseDTO($this->partContentGateway->findDraft(
            $useCaseRequest->getCourseId(),
            $useCaseRequest->getPartId()
        ));
    }
}
