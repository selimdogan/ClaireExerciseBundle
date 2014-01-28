<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Part;

use OC\CLAIRE\BusinessRules\Requestors\Course\Part\GetPublishedPartRequest;
use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;
use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetPublishedPart extends GetPart
{
    /**
     * @return UseCaseResponse
     */
    public function execute(UseCaseRequest $useCaseRequest)
    {
        /** @var GetPublishedPartRequest $useCaseRequest */
        $this->part = $this->partGateway->findPublished(
            $useCaseRequest->getCourseIdentifier(),
            $useCaseRequest->getPartIdentifier()
        );

        $this->buildResponse();

        return $this->response;
    }

}
