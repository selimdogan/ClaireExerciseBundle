<?php

namespace SimpleIT\ClaireAppBundle\Gateways\Course\Course;

use SimpleIT\ApiResourcesBundle\Course\CourseResource;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CourseGatewayDummy implements CourseGateway
{
    /**
     * @return CourseResource
     */
    public function findPublished($courseIdentifier)
    {
        return null;
    }

    public function updateToWaitingForPublication($courseId)
    {
    }

    public function updateToPublished($courseId)
    {
    }

}
