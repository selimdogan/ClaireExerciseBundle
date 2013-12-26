<?php

namespace SimpleIT\ClaireAppBundle\Requestors\Course\Course;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface GetDraftCourseRequest extends GetCourseRequest
{
    /**
     * @return int
     */
    public function getCourseId();
}
