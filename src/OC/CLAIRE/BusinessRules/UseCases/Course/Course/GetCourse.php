<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Course;

use SimpleIT\ApiResourcesBundle\Course\CourseResource;
use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseGateway;
use OC\CLAIRE\BusinessRules\Requestors\UseCase;
use OC\CLAIRE\BusinessRules\Responders\Course\Course\GetCourseResponse;
use OC\CLAIRE\BusinessRules\UseCases\Course\Course\DTO\GetCourseResponseDTO;

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
