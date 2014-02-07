<?php

namespace OC\CLAIRE\BusinessRules\Responders\Course\Course;

use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface GetCourseStatusesResponse extends UseCaseResponse
{
    /**
     * @return GetCourseResponse[]
     */
    public function getCourses();

    /**
     * @return GetCourseResponse
     * @throws \OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException
     */
    public function getDraftCourse();

    /**
     * @return GetCourseResponse
     * @throws \OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException
     */
    public function getWaitingFoPublicationCourse();

    /**
     * @return GetCourseResponse
     * @throws \OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException
     */
    public function getPublishedCourse();
}
