<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Difficulty;

use OC\CLAIRE\BusinessRules\Entities\Difficulty\Difficulty;
use OC\CLAIRE\BusinessRules\Gateways\Course\Difficulty\CourseDifficultyGatewaySpy;
use OC\CLAIRE\BusinessRules\Gateways\Course\Difficulty\CourseNotFoundCourseDifficultyGatewayStub;
use OC\CLAIRE\BusinessRules\UseCases\Course\Difficulty\DTO\SaveCourseDifficultyRequestDTO;

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
     * @var CourseDifficultyGatewaySpy
     */
    private $courseDifficultyGateway;

    /**
     * @test
     * @expectedException \OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException
     */
    public function NonExistingCourse_ThrowException()
    {
        $this->useCase->setCourseDifficultyGateway(new CourseNotFoundCourseDifficultyGatewayStub());
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
        $this->courseDifficultyGateway = new CourseDifficultyGatewaySpy();
        $this->useCase->setCourseDifficultyGateway($this->courseDifficultyGateway);
        $this->executeUseCase(
            new SaveCourseDifficultyRequestDTO(self::COURSE_1_ID, self::COURSE_1_DIFFICULTY)
        );
        $this->assertDifficulty();
    }

    private function assertDifficulty()
    {
        $this->assertEquals(self::COURSE_1_ID, $this->courseDifficultyGateway->updatedCourseId);
        $this->assertEquals(
            self::COURSE_1_DIFFICULTY,
            $this->courseDifficultyGateway->updatedDifficulty
        );
    }

    protected function setup()
    {
        $this->useCase = new SaveCourseDifficulty();
    }
}
