<?php

namespace SimpleIT\ClaireAppBundle\UseCases\Course\Workflow;

use SimpleIT\ClaireAppBundle\Gateways\Course\Course\CourseGateway;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CourseGatewayStub1 implements CourseGateway
{
    public function updateToWaitingForPublication($courseId)
    {
    }

    public function updateToPublished($courseId)
    {
    }

}
