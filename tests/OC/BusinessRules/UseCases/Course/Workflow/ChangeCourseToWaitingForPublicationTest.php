<?php
namespace OC\BusinessRules\UseCases\Course\Workflow;

use OC\BusinessRules\Gateways\Course\Course\CourseGatewayDummy;
use OC\BusinessRules\UseCases\Course\Workflow\DTO\ChangeCourseStatusRequestDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class ChangeCourseToWaitingForPublicationTest extends ChangeCourseStatusTest
{

    protected function setUp()
    {
        $this->useCase = new ChangeCourseToWaitingForPublication();
        $this->request = new ChangeCourseStatusRequestDTO(1);
        $this->useCase->setCourseGateway(new CourseGatewayDummy());
    }

}
