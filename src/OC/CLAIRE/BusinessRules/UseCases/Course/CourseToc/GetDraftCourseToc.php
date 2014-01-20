<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\CourseToc;

use OC\CLAIRE\BusinessRules\Requestors\Course\CourseToc\GetDraftCourseTocRequest;
use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;
use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;
use OC\CLAIRE\BusinessRules\UseCases\Course\CourseToc\DTO\GetCourseTocResponseDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftCourseToc extends GetCourseToc
{
    /**
     * @return UseCaseResponse
     */
    public function execute(UseCaseRequest $useCaseRequest)
    {
        /** @var GetDraftCourseTocRequest $useCaseRequest */

        return new GetCourseTocResponseDTO($this->tocByCourseGateway->findDraft(
            $useCaseRequest->getCourseId()
        ));
    }

}
