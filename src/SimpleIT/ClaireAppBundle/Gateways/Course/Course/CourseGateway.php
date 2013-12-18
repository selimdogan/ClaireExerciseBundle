<?php

namespace SimpleIT\ClaireAppBundle\Gateways\Course\Course;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface CourseGateway
{
    public function updateToWaitingForPublication($courseId);

    public function updateToPublished($courseId);
}
