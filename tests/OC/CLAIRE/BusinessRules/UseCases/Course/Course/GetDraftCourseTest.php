<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Course;

use OC\CLAIRE\BusinessRules\Entities\Course\Course\SmallEasyDraftCourseStub;
use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseGatewayStub;
use OC\CLAIRE\BusinessRules\UseCases\Course\Course\DTO\GetDraftCourseRequestDTO;

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
            SmallEasyDraftCourseStub::COURSE_STATUS,
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
