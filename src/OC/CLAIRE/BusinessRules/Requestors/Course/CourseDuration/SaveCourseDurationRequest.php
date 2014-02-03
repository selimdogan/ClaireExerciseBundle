<?php

namespace OC\CLAIRE\BusinessRules\Requestors\Course\CourseDuration;

use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface SaveCourseDurationRequest extends UseCaseRequest
{
    /**
     * @return int
     */
    public function getCourseId();

    /**
     * @return \DateInterval
     */
    public function getDuration();
}
