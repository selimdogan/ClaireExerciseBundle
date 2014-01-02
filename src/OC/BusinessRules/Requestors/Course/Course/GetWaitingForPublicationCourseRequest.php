<?php

namespace OC\BusinessRules\Requestors\Course\Course;

use OC\BusinessRules\Requestors\Course\Course\GetCourseRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface GetWaitingForPublicationCourseRequest extends GetCourseRequest
{
    /**
     * @return int
     */
    public function getCourseId();
}
