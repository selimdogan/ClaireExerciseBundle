<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Course;

use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseGateway;
use OC\CLAIRE\BusinessRules\Requestors\UseCase;
use OC\CLAIRE\BusinessRules\Responders\Course\Course\GetCourseResponse;
use OC\CLAIRE\BusinessRules\UseCases\Course\Course\DTO\GetCourseResponseBuilderImpl;
use SimpleIT\ApiResourcesBundle\Course\CourseResource;

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
    protected function buildCourseResponse(CourseResource $course)
    {
        $response = GetCourseResponseBuilderImpl::create()
            ->course($course->getId())
            ->created($course->getCreatedAt())
            ->named($course->getTitle())
            ->rated($course->getRating())
            ->updated($course->getUpdatedAt())
            ->withDescription($course->getDescription())
            ->withDifficulty($course->getDifficulty())
            ->withDisplayLevel($course->getDisplayLevel())
            ->withDuration($course->getDuration())
            ->withImage($course->getImage())
            ->withLicense($course->getLicense())
            ->withSlug($course->getSlug())
            ->withStatus($course->getStatus())
            ->build();

        return $response;
    }
}
