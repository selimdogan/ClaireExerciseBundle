<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Part;

use OC\CLAIRE\BusinessRules\Requestors\Course\Part\GetWaitingForPublicationPartRequest;
use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;
use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetWaitingForPublicationPart extends GetPart
{
    /**
     * @return UseCaseResponse
     */
    public function execute(UseCaseRequest $useCaseRequest)
    {
        /** @var GetWaitingForPublicationPartRequest $useCaseRequest */
        $this->part = $this->partGateway->findWaitingForPublication(
            $useCaseRequest->getCourseId(),
            $useCaseRequest->getPartId()
        );

        $this->buildResponse();

        return $this->response;
    }

}
