<?php

namespace SimpleIT\ClaireAppBundle\UseCases\Course\Workflow;

use SimpleIT\ClaireAppBundle\UseCases\Course\Workflow\DTO\ChangeCourseStatusRequestDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class ChangeCourseToPublishedTest extends ChangeCourseStatusTest
{
    public function setUp()
    {
        $this->useCase = new ChangeCourseToPublished();
        $this->request = new ChangeCourseStatusRequestDTO(1);
        $this->useCase->setCourseGateway(new CourseGatewayDummy());
    }
}
