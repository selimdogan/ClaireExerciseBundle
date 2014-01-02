<?php

namespace OC\BusinessRules\UseCases\Course\Workflow;

use OC\BusinessRules\Gateways\Course\Course\CourseGatewayDummy;
use OC\BusinessRules\UseCases\Course\Workflow\DTO\ChangeCourseStatusRequestDTO;

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
