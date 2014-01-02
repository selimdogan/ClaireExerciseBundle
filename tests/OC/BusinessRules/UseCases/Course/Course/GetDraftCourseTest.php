<?php

namespace OC\BusinessRules\UseCases\Course\Course;

use OC\BusinessRules\Entities\Course\DraftCourseStub;
use OC\BusinessRules\Gateways\Course\Course\CourseGatewayStub;
use OC\BusinessRules\UseCases\Course\Course\DTO\GetDraftCourseRequestDTO;

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
