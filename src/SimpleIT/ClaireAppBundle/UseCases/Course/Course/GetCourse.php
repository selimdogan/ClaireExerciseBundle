<?php

namespace SimpleIT\ClaireAppBundle\UseCases\Course\Course;

use SimpleIT\ApiResourcesBundle\Course\CourseResource;
use SimpleIT\ClaireAppBundle\Gateways\Course\Course\CourseGateway;
use SimpleIT\ClaireAppBundle\Requestors\UseCase;
use SimpleIT\ClaireAppBundle\Responders\Course\Course\GetCourseResponse;
use SimpleIT\ClaireAppBundle\UseCases\Course\Course\DTO\GetCourseResponseDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
abstract class GetCourse implements UseCase
{
    /**
     * @var CourseGateway
     */
    protected $courseGateway;

    public function setCourseGateway(CourseGateway $courseGateway)
    {
        $this->courseGateway = $courseGateway;
    }

    /**
     * @return GetCourseResponse
     */
    protected function buildResponse(CourseResource $course)
    {
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
}
