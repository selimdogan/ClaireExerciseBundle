<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\CourseDuration;

use OC\CLAIRE\BusinessRules\Entities\Course\Course\SmallEasyDraftCourseStub;
use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseGatewayStub;
use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseNotFoundCourseGatewayStub;
use OC\CLAIRE\BusinessRules\Gateways\Course\Course\EmptyCourseGatewayStub;
use OC\CLAIRE\BusinessRules\Responders\Course\CourseDuration\GetDraftCourseDurationResponse;
use OC\CLAIRE\BusinessRules\UseCases\Course\CourseDuration\DTO\GetDraftCourseDurationRequestDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftCourseDurationTest extends \PHPUnit_Framework_TestCase
{
    const NON_EXISTING_COURSE_ID = 999;

    const WITHOUT_DURATION_COURSE_ID = 998;

    const COURSE_1_ID = 1;

    /**
     * @var GetDraftCourseDurationResponse
     */
    private $response;

    /**
     * @var GetDraftCourseDuration
     */
    private $useCase;

    /**
     * @test
     * @expectedException \OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException
     */
    public function NonExistingCourse_ThrowException()
    {
        $this->useCase->setCourseGateway(new CourseNotFoundCourseGatewayStub());
        $this->executeUseCase(new GetDraftCourseDurationRequestDTO(self::NON_EXISTING_COURSE_ID));
    }

    private function executeUseCase($request)
    {
        $this->response = $this->useCase->execute($request);
    }

    /**
     * @test
     */
    public function WithoutDuration_ReturnNoDuration()
    {
        $this->useCase->setCourseGateway(new EmptyCourseGatewayStub());
        $this->executeUseCase(
            new GetDraftCourseDurationRequestDTO(self::WITHOUT_DURATION_COURSE_ID)
        );
        $this->assertNull($this->response->getCourseDuration());
    }

    /**
     * @test
     */
    public function ReturnDuration()
    {
        $this->useCase->setCourseGateway(new CourseGatewayStub());
        $this->executeUseCase(
            new GetDraftCourseDurationRequestDTO(self::COURSE_1_ID)
        );
        $this->assertEquals(
            SmallEasyDraftCourseStub::DURATION,
            $this->response->getCourseDuration()
        );

    }

    protected function setUp()
    {
        $this->useCase = new GetDraftCourseDuration();
    }

}
