<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Course\DTO;

use OC\CLAIRE\BusinessRules\Entities\Course\Course\Status;
use OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException;
use OC\CLAIRE\BusinessRules\Responders\Course\Course\GetCourseResponse;
use OC\CLAIRE\BusinessRules\Responders\Course\Course\GetCourseStatusesResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetCourseStatusesResponseDTO implements GetCourseStatusesResponse
{
    /**
     * @var GetCourseResponse[]
     */
    public $courses = array();

    /**
     * @return GetCourseResponse[]
     */
    public function getCourses()
    {
        return $this->courses;
    }

    /**
     * @return GetCourseResponse
     * @throws \OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException
     */
    public function getDraftCourse()
    {
        if (!isset ($this->courses[Status::DRAFT])) {
            throw new CourseNotFoundException();
        }

        return $this->courses[Status::DRAFT];
    }

    /**
     * @return GetCourseResponse
     * @throws \OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException
     */
    public function getWaitingFoPublicationCourse()
    {
        if (!isset ($this->courses[Status::WAITING_FOR_PUBLICATION])) {
            throw new CourseNotFoundException();
        }

        return $this->courses[Status::WAITING_FOR_PUBLICATION];
    }

    /**
     * @return GetCourseResponse
     * @throws \OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException
     */
    public function getPublishedCourse()
    {
        if (!isset ($this->courses[Status::PUBLISHED])) {
            throw new CourseNotFoundException();
        }

        return $this->courses[Status::PUBLISHED];
    }
}
