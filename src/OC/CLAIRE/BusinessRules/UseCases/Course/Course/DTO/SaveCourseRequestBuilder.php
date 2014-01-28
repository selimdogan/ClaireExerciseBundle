<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Course\DTO;

use OC\CLAIRE\BusinessRules\Requestors\Course\Course\SaveCourseRequest;
use OC\CLAIRE\BusinessRules\Requestors\Course\Course\SaveCourseRequestException;
use OC\CLAIRE\BusinessRules\Requestors\RequestBuilder;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class SaveCourseRequestBuilder implements RequestBuilder
{
    /**
     * @var SaveCourseRequestDTO
     */
    private $saveCourseRequest;

    private function __construct()
    {
        $this->saveCourseRequest = new SaveCourseRequestDTO();
    }

    public static function create()
    {
        return new SaveCourseRequestBuilder();
    }

    public function course($courseId)
    {
        $this->saveCourseRequest->courseId = $courseId;

        return $this;
    }

    public function withDescription($description)
    {
        $this->saveCourseRequest->courseDescription = $description;

        return $this;
    }

    public function withDifficulty($difficulty)
    {
        $this->saveCourseRequest->courseDifficulty = $difficulty;

        return $this;
    }

    public function withDisplayLevel($displayLevel)
    {
        $this->saveCourseRequest->courseDisplayLevel = $displayLevel;

        return $this;
    }

    public function withDuration($duration)
    {
        $this->saveCourseRequest->courseDuration = $duration;

        return $this;
    }

    public function withImage($image)
    {
        $this->saveCourseRequest->courseImage = $image;

        return $this;
    }

    public function withTitle($title)
    {
        $this->saveCourseRequest->courseTitle = $title;

        return $this;
    }

    /**
     * @return SaveCourseRequest
     * @throws \OC\CLAIRE\BusinessRules\Requestors\Course\Course\SaveCourseRequestException
     */
    public function build()
    {
        if (null === $this->saveCourseRequest->courseId) {
            throw new SaveCourseRequestException('Course id is required');
        }

        return $this->saveCourseRequest;
    }

}
