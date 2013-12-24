<?php

namespace SimpleIT\ClaireAppBundle\Gateways\Course\Course;

use SimpleIT\ApiResourcesBundle\Course\CourseResource;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface CourseGateway
{
    /**
     * @return CourseResource
     */
    public function findPublished($courseIdentifier);

    public function updateToWaitingForPublication($courseId);

    public function updateToPublished($courseId);
}
