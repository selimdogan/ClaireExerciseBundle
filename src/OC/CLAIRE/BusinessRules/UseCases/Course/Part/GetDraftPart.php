<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Part;

use OC\CLAIRE\BusinessRules\Requestors\Course\Part\GetDraftPartRequest;
use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;
use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftPart extends GetPart
{
    /**
     * @return UseCaseResponse
     */
    public function execute(UseCaseRequest $useCaseRequest)
    {
        /** @var GetDraftPartRequest $useCaseRequest */
        $this->part = $this->partGateway->findDraft(
            $useCaseRequest->getCourseId(),
            $useCaseRequest->getPartId()
        );

        $this->buildResponse();

        return $this->response;
    }

}
