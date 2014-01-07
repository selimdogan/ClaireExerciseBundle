<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Workflow;

use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseGatewayDummy;
use OC\CLAIRE\BusinessRules\UseCases\Course\Workflow\DTO\ChangeCourseStatusRequestDTO;

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
