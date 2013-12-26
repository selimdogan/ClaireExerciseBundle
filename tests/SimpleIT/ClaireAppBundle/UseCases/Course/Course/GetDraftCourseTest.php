<?php

namespace SimpleIT\ClaireAppBundle\UseCases\Course\Course;

use SimpleIT\ClaireAppBundle\Entities\Course\DraftCourseStub;
use SimpleIT\ClaireAppBundle\Gateways\Course\Course\CourseGatewayStub;
use SimpleIT\ClaireAppBundle\UseCases\Course\Course\DTO\GetDraftCourseRequestDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftCourseTest extends GetCourseTest
{
    /**
     * @test
     */
    public function ReturnCourse()
    {
        $this->executeUseCase();
        $this->assertCourse();
        $this->assertEquals(
            DraftCourseStub::COURSE_STATUS,
            $this->response->getStatus()
        );
    }

    protected function setUp()
    {
        $this->useCase = new GetDraftCourse();
        $this->request = new GetDraftCourseRequestDTO(self::COURSE_ID);
        $this->useCase->setCourseGateway(new CourseGatewayStub());
    }
}
