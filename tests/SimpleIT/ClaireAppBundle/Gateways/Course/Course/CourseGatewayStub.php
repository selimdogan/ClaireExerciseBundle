<?php

namespace SimpleIT\ClaireAppBundle\Gateways\Course\Course;

use SimpleIT\ApiResourcesBundle\Course\CourseResource;
use SimpleIT\ClaireAppBundle\Entities\Course\PublishedCourseStub;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CourseGatewayStub implements CourseGateway
{
    /**
     * @return CourseResource
     */
    public function findPublished($courseIdentifier)
    {
        return new PublishedCourseStub();
    }

    public function updateToWaitingForPublication($courseId)
    {
        return null;
    }

    public function updateToPublished($courseId)
    {
        return null;
    }

}
