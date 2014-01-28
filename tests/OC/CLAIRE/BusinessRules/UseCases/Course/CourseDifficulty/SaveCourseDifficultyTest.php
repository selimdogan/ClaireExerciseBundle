<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\CourseDifficulty;

use OC\CLAIRE\BusinessRules\Entities\Course\Course\Difficulty;
use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseGatewaySpy;
use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseNotFoundCourseGatewayStub;
use OC\CLAIRE\BusinessRules\UseCases\Course\CourseDifficulty\DTO\SaveCourseDifficultyRequestDTO;
use SimpleIT\ClaireAppBundle\Entity\Course\Course\CourseFactoryImpl;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class SaveCourseDifficultyTest extends \PHPUnit_Framework_TestCase
{
    const NON_EXISTING_COURSE_ID = 999;

    const COURSE_1_ID = 1;

    const COURSE_1_DIFFICULTY = Difficulty::EASY;

    /**
     * @var SaveCourseDifficulty
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
            new SaveCourseDifficultyRequestDTO(self::NON_EXISTING_COURSE_ID, Difficulty::EASY)
        );
    }

    private function executeUseCase($request)
    {
        $this->useCase->execute($request);
    }

    /**
     * @test
     */
    public function SaveDifficulty()
    {
        $this->courseGateway = new CourseGatewaySpy();
        $this->useCase->setCourseGateway($this->courseGateway);
        $this->executeUseCase(
            new SaveCourseDifficultyRequestDTO(self::COURSE_1_ID, self::COURSE_1_DIFFICULTY)
        );
        $this->assertDifficulty();
    }

    private function assertDifficulty()
    {
        $this->assertEquals(self::COURSE_1_ID, $this->courseGateway->courseId);
        $this->assertEquals(
            self::COURSE_1_DIFFICULTY,
            $this->courseGateway->course->getDifficulty()
        );
    }

    protected function setup()
    {
        $this->useCase = new SaveCourseDifficulty();
        $this->useCase->setCourseFactory(new CourseFactoryImpl());
    }
}
