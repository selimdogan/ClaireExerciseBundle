<?php

namespace SimpleIT\ClaireAppBundle\UseCases\Course\Course;

use SimpleIT\ClaireAppBundle\Entities\Course\WaitingForPublicationCourseStub;
use SimpleIT\ClaireAppBundle\Gateways\Course\Course\CourseGatewayStub;
use SimpleIT\ClaireAppBundle\UseCases\Course\Course\DTO\GetWaitingForPublicationCourseRequestDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetWaitingForPublicationCourseTest extends GetCourseTest
{
    /**
     * @test
     */
    public function ReturnCourse()
    {
        $this->executeUseCase();
        $this->assertCourse();
        $this->assertEquals(
            WaitingForPublicationCourseStub::COURSE_STATUS,
            $this->response->getStatus()
        );
    }

    protected function setUp()
    {
        $this->useCase = new GetWaitingForPublicationCourse();
        $this->request = new GetWaitingForPublicationCourseRequestDTO(self::COURSE_ID);
        $this->useCase->setCourseGateway(new CourseGatewayStub());
    }
}
