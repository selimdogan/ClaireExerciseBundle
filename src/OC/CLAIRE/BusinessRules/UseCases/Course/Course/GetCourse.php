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
        $response = new GetCourseResponseDTO();
        $response->createdAt = $course->getCreatedAt();
        $response->description = $course->getDescription();
        $response->difficulty = $course->getDifficulty();
        $response->displayLevel = $course->getDisplayLevel();
        $response->duration = $course->getDuration();
        $response->id = $course->getId();
        $response->image = $course->getImage();
        $response->license = $course->getLicense();
        $response->slug = $course->getSlug();
        $response->status = $course->getStatus();
        $response->title = $course->getTitle();
        $response->updatedAt = $course->getUpdatedAt();

        return $response;
    }
}
