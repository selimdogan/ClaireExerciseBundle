<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\CourseToc;

use OC\CLAIRE\BusinessRules\Requestors\Course\CourseToc\GetPublishedCourseTocRequest;
use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;
use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;
use OC\CLAIRE\BusinessRules\UseCases\Course\CourseToc\DTO\GetCourseTocResponseDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetPublishedCourseToc extends GetCourseToc
{
    /**
     * @return UseCaseResponse
     */
    public function execute(UseCaseRequest $useCaseRequest)
    {
        /** @var GetPublishedCourseTocRequest $useCaseRequest */

        return new GetCourseTocResponseDTO($this->tocByCourseGateway->findPublished(
            $useCaseRequest->getCourseIdentifier()
        ));
    }

}
