<?php

namespace SimpleIT\ClaireAppBundle\UseCases\Course\Course;

use SimpleIT\ClaireAppBundle\Requestors\Course\Course\GetDraftCourseRequest;
use SimpleIT\ClaireAppBundle\Requestors\UseCaseRequest;
use SimpleIT\ClaireAppBundle\Responders\UseCaseResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftCourse extends GetCourse
{
    /**
     * @return UseCaseResponse
     */
    public function execute(UseCaseRequest $useCaseRequest)
    {
        /** @var GetDraftCourseRequest $useCaseRequest */
        $course = $this->courseGateway->findDraft($useCaseRequest->getCourseId());

        return $this->buildResponse($course);
    }

}
