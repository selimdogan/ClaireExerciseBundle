<?php

namespace SimpleIT\ClaireAppBundle\UseCases\Course\Course;

use SimpleIT\ClaireAppBundle\Gateways\Course\Course\CourseGateway;
use SimpleIT\ClaireAppBundle\Requestors\Course\Course\GetPublishedCourseRequest;
use SimpleIT\ClaireAppBundle\Requestors\UseCaseRequest;
use SimpleIT\ClaireAppBundle\Responders\UseCaseResponse;
use SimpleIT\ClaireAppBundle\UseCases\Course\Course\DTO\GetCourseResponseDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetPublishedCourse extends GetCourse
{
    /**
     * @var CourseGateway
     */
    private $courseGateway;

    /**
     * @return UseCaseResponse
     */
    public function execute(UseCaseRequest $useCaseRequest)
    {
        /** @var GetPublishedCourseRequest $useCaseRequest */
        $course = $this->courseGateway->findPublished($useCaseRequest->getCourseIdentifier());
        $response = new GetCourseResponseDTO(
            $course->getId(),
            $course->getSlug(),
            $course->getStatus(),
            $course->getTitle(),
            $course->getDisplayLevel(),
            $course->getCreatedAt(),
            $course->getUpdatedAt()
        );

        return $response;
    }

    public function setCourseGateway(CourseGateway $courseGateway)
    {
        $this->courseGateway = $courseGateway;
    }

}
