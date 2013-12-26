<?php

namespace SimpleIT\ClaireAppBundle\Requestors\Course\Course;

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
