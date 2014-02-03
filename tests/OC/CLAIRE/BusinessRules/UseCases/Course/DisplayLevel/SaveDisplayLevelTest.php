<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\DisplayLevel;

use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseGatewaySpy;
use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseNotFoundCourseGatewayStub;
use OC\CLAIRE\BusinessRules\UseCases\Course\DisplayLevel\DTO\SaveDisplayLevelRequestDTO;
use SimpleIT\ClaireAppBundle\Entity\Course\Course\CourseFactoryImpl;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class SaveDisplayLevelTest extends \PHPUnit_Framework_TestCase
{
    const NON_EXISTING_COURSE_ID = 999;

    const COURSE_1_ID = 1;

    const COURSE_1_DISPLAY_LEVEL = 1;

    /**
     * @var SaveDisplayLevel
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
        $this->useCase->execute(new SaveDisplayLevelRequestDTO(self::NON_EXISTING_COURSE_ID, null));
    }

    /**
     * @test
     */
    public function SaveDisplayLevel()
    {
        $this->courseGateway = new CourseGatewaySpy();
        $this->useCase->setCourseGateway($this->courseGateway);
        $this->useCase->execute(
            new SaveDisplayLevelRequestDTO(self::COURSE_1_ID, self::COURSE_1_DISPLAY_LEVEL)
        );
        $this->assertDifficulty();
    }

    private function assertDifficulty()
    {
        $this->assertEquals(self::COURSE_1_ID, $this->courseGateway->courseId);
        $this->assertEquals(
            self::COURSE_1_DISPLAY_LEVEL,
            $this->courseGateway->course->getDisplayLevel()
        );
    }

    protected function setup()
    {
        $this->useCase = new SaveDisplayLevel();
        $this->useCase->setCourseFactory(new CourseFactoryImpl());
    }
}
