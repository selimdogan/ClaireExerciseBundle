<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\PartContent;

use OC\CLAIRE\BusinessRules\Requestors\Course\PartContent\GetPublishedPartContentRequest;
use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;
use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;
use OC\CLAIRE\BusinessRules\UseCases\Course\PartContent\DTO\GetPartContentResponseDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetPublishedPartContent extends GetPartContent
{
    /**
     * @return UseCaseResponse
     */
    public function execute(UseCaseRequest $useCaseRequest)
    {
        /** @var GetPublishedPartContentRequest $useCaseRequest */

        return new GetPartContentResponseDTO($this->partContentGateway->findDraft(
            $useCaseRequest->getCourseIdentifier(),
            $useCaseRequest->getPartIdentifier()
        ));
    }

}
