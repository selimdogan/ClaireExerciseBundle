<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\CourseDuration;

use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseGatewaySpy;
use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseNotFoundCourseGatewayStub;
use OC\CLAIRE\BusinessRules\UseCases\Course\CourseDuration\DTO\SaveCourseDurationRequestDTO;
use SimpleIT\ClaireAppBundle\Entity\Course\Course\CourseFactoryImpl;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class SaveCourseDurationTest extends \PHPUnit_Framework_TestCase
{
    const NON_EXISTING_COURSE_ID = 999;

    const WITHOUT_DURATION_COURSE_ID = 998;

    const COURSE_1_ID = 1;

    const COURSE_1_DURATION = 'P1D';

    /**
     * @var SaveCourseDuration
     */
    private $useCase;

    /**
     * @test
     * @expectedException \OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException
     */
    public function NonExistingCourse_ThrowException()
    {
        $this->useCase->setCourseGateway(new CourseNotFoundCourseGatewayStub());
        $this->executeUseCase(
            new SaveCourseDurationRequestDTO(self::NON_EXISTING_COURSE_ID, new \DateInterval(self::COURSE_1_DURATION))
        );
    }

    private function executeUseCase($request)
    {
        $this->useCase->execute($request);
    }

    /**
     * @test
     */
    public function SaveDuration()
    {
        $courseGateway = new CourseGatewaySpy();
        $this->useCase->setCourseGateway($courseGateway);
        $this->executeUseCase(
            new SaveCourseDurationRequestDTO(self::COURSE_1_ID, new \DateInterval(self::COURSE_1_DURATION))
        );
        $this->assertEquals(self::COURSE_1_ID, $courseGateway->courseId);
        $this->assertEquals(
            new \DateInterval(self::COURSE_1_DURATION),
            $courseGateway->course->getDuration()
        );
    }

    protected function setUp()
    {
        $this->useCase = new SaveCourseDuration();
        $this->useCase->setCourseFactory(new CourseFactoryImpl());
    }

}
