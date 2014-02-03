<?php

namespace OC\CLAIRE\BusinessRules\Requestors\Course\Course;

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
