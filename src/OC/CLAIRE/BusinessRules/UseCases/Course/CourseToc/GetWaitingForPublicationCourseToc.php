<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\CourseToc;

use OC\CLAIRE\BusinessRules\Requestors\Course\CourseToc\GetWaitingForPublicationCourseTocRequest;
use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;
use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;
use OC\CLAIRE\BusinessRules\UseCases\Course\CourseToc\DTO\GetCourseTocResponseDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetWaitingForPublicationCourseToc extends GetCourseToc
{
    /**
     * @return UseCaseResponse
     */
    public function execute(UseCaseRequest $useCaseRequest)
    {
        /** @var GetWaitingForPublicationCourseTocRequest $useCaseRequest */

        return new GetCourseTocResponseDTO($this->tocByCourseGateway->findWaitingForPublication(
            $useCaseRequest->getCourseId()
        ));
    }

}
