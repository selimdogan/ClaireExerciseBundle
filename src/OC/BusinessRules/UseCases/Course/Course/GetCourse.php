<?php

namespace OC\BusinessRules\UseCases\Course\Course;

use SimpleIT\ApiResourcesBundle\Course\CourseResource;
use OC\BusinessRules\Gateways\Course\Course\CourseGateway;
use OC\BusinessRules\Requestors\UseCase;
use OC\BusinessRules\Responders\Course\Course\GetCourseResponse;
use OC\BusinessRules\UseCases\Course\Course\DTO\GetCourseResponseDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
abstract class GetCourse implements UseCase
{
    /**
     * @var \OC\BusinessRules\Gateways\Course\Course\CourseGateway
     */
    protected $courseGateway;

    public function setCourseGateway(\OC\BusinessRules\Gateways\Course\Course\CourseGateway $courseGateway)
    {
        $this->courseGateway = $courseGateway;
    }

    /**
     * @return \OC\BusinessRules\Responders\Course\Course\GetCourseResponse
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
