<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Course\DTO;

use OC\CLAIRE\BusinessRules\Entities\Course\Course\Status;
use OC\CLAIRE\BusinessRules\Responders\Course\Course\GetCourseResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetCourseStatusesResponseBuilderImpl
{
    /**
     * @var GetCourseStatusesResponseDTO
     */
    private $getCourseStatusesResponse;

    private function __construct()
    {
        $this->getCourseStatusesResponse = new GetCourseStatusesResponseDTO();
    }

    public static function create()
    {
        return new GetCourseStatusesResponseBuilderImpl();
    }

    public function withDraft(GetCourseResponse $course)
    {
        $this->getCourseStatusesResponse->courses[Status::DRAFT] = $course;

        return $this;
    }

    public function withWaitingForPublication(GetCourseResponse $course)
    {
        $this->getCourseStatusesResponse->courses[Status::WAITING_FOR_PUBLICATION] = $course;

        return $this;
    }

    public function withPublished(GetCourseResponse $course)
    {
        $this->getCourseStatusesResponse->courses[Status::PUBLISHED] = $course;

        return $this;
    }

    public function build()
    {
        return $this->getCourseStatusesResponse;
    }
}
