<?php

namespace OC\CLAIRE\BusinessRules\Requestors\Course\Course;

use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface SaveCourseRequest extends UseCaseRequest
{
    /**
     * @return string
     */
    public function getCourseDescription();

    /**
     * @return string
     */
    public function getCourseDifficulty();

    /**
     * @return string
     */
    public function getCourseDisplayLevel();

    /**
     * @return string
     */
    public function getCourseDuration();

    /**
     * @return int
     */
    public function getCourseId();

    /**
     * @return string
     */
    public function getCourseImage();

    /**
     * @return string
     */
    public function getCourseTitle();
}
