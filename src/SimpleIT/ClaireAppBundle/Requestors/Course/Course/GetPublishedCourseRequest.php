<?php

namespace SimpleIT\ClaireAppBundle\Requestors\Course\Course;

use SimpleIT\ClaireAppBundle\Requestors\UseCaseRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface GetPublishedCourseRequest extends UseCaseRequest
{
    /**
     * @return int|string
     */
    public function getCourseIdentifier();
}
