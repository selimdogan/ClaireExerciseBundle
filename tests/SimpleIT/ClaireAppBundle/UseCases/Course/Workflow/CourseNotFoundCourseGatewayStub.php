<?php

namespace SimpleIT\ClaireAppBundle\UseCases\Course\Workflow;

use SimpleIT\ClaireAppBundle\Gateways\Course\Course\CourseGateway;
use SimpleIT\ClaireAppBundle\Gateways\Course\Course\CourseNotFoundException;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CourseNotFoundCourseGatewayStub implements CourseGateway
{
    public function updateToWaitingForPublication($courseId)
    {
        throw new CourseNotFoundException();
    }

    public function updateToPublished($courseId)
    {
        throw new CourseNotFoundException();
    }

}
