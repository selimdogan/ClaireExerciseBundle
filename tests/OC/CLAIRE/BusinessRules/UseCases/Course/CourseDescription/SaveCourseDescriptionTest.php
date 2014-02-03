<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\CourseDescription;

use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseGatewaySpy;
use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseNotFoundCourseGatewayStub;
use OC\CLAIRE\BusinessRules\UseCases\Course\CourseDescription\DTO\SaveCourseDescriptionRequestDTO;
use SimpleIT\ClaireAppBundle\Entity\Course\Course\CourseFactoryImpl;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class SaveCourseDescriptionTest extends \PHPUnit_Framework_TestCase
{
    const COURSE_1_ID = 1;

    const NON_EXISTING_COURSE_ID = 999;

    const COURSE_1_DESCRIPTION = 'Course description';

    /**
     * @var SaveCourseDescription
     */
    private $useCase;

    /**
     * @var CourseGatewaySpy
     */
    private $courseGateway;

    /**
     * @test
     * @expectedException \OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException
     */
    public function NonExistingCourse_ThrowException()
    {
        $this->useCase->setCourseGateway(new CourseNotFoundCourseGatewayStub());
        $this->executeUseCase(
            new SaveCourseDescriptionRequestDTO(self::NON_EXISTING_COURSE_ID, self::COURSE_1_DESCRIPTION)
        );
    }

    private function executeUseCase($request)
    {
        $this->useCase->execute($request);
    }

    /**
     * @test
     */
    public function SaveDescription()
    {
        $this->courseGateway = new CourseGatewaySpy();
        $this->useCase->setCourseGateway($this->courseGateway);
        $this->executeUseCase(
            new SaveCourseDescriptionRequestDTO(self::COURSE_1_ID, self::COURSE_1_DESCRIPTION)
        );
        $this->assertDescription();
    }

    private function assertDescription()
    {
        $this->assertEquals(self::COURSE_1_ID, $this->courseGateway->courseId);
        $this->assertEquals(
            self::COURSE_1_DESCRIPTION,
            $this->courseGateway->course->getDescription()
        );
    }

    protected function setup()
    {
        $this->useCase = new SaveCourseDescription();
        $this->useCase->setCourseFactory(new CourseFactoryImpl());
    }
}
