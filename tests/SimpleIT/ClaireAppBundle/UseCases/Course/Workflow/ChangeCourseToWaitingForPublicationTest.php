<?php
use SimpleIT\ClaireAppBundle\UseCases\Course\Workflow\ChangeCourseStatusTest;
use SimpleIT\ClaireAppBundle\UseCases\Course\Workflow\ChangeCourseToWaitingForPublication;
use SimpleIT\ClaireAppBundle\UseCases\Course\Workflow\CourseGatewayDummy;
use SimpleIT\ClaireAppBundle\UseCases\Course\Workflow\DTO\ChangeCourseStatusRequestDTO;

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
