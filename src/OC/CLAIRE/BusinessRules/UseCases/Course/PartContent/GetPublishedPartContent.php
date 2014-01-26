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
        $content = $this->partContentGateway->findPublished(
            $useCaseRequest->getCourseIdentifier(),
            $useCaseRequest->getPartIdentifier()
        );

        return new GetPartContentResponseDTO($content);
    }

}
