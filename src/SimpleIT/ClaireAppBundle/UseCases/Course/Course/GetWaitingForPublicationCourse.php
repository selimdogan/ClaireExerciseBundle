<?php

namespace SimpleIT\ClaireAppBundle\UseCases\Course\Course;

use SimpleIT\ClaireAppBundle\Requestors\Course\Course\GetWaitingForPublicationCourseRequest;
use SimpleIT\ClaireAppBundle\Requestors\UseCaseRequest;
use SimpleIT\ClaireAppBundle\Responders\UseCaseResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetWaitingForPublicationCourse extends GetCourse
{
    /**
     * @return UseCaseResponse
     */
    public function execute(UseCaseRequest $useCaseRequest)
    {
        /** @var GetWaitingForPublicationCourseRequest $useCaseRequest */
        $course = $this->courseGateway->findWaitingForPublication($useCaseRequest->getCourseId());

        return $this->buildResponse($course);
    }

}
