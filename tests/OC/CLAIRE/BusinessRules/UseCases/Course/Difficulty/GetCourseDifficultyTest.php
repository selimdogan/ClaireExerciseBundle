<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Difficulty;

use OC\CLAIRE\BusinessRules\Entities\Difficulty\Difficulty;
use OC\CLAIRE\BusinessRules\Gateways\Course\Difficulty\CourseDifficultyGatewaySpy;
use OC\CLAIRE\BusinessRules\Gateways\Course\Difficulty\CourseNotFoundCourseDifficultyGatewayStub;
use OC\CLAIRE\BusinessRules\Gateways\Course\Difficulty\WithoutDifficultyCourseDifficultyGatewayStub;
use OC\CLAIRE\BusinessRules\Responders\Course\Difficulty\GetCourseDifficultyResponse;
use OC\CLAIRE\BusinessRules\UseCases\Course\Difficulty\DTO\GetCourseDifficultyRequestDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetCourseDifficultyTest extends \PHPUnit_Framework_TestCase
{
    const WITHOUT_DIFFICULTY_COURSE_ID = 998;

    const EASY_COURSE_ID = 1;

    const NON_EXISTING_COURSE_ID = 999;

    /**
     * @var GetCourseDifficultyResponse
     */
    private $response;

    /**
     * @var GetCourseDifficulty
     */
    private $useCase;

    /**
     * @test
     * @expectedException \OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException
     */
    public function NonExistingCourse_ThrowException()
    {
        $this->useCase->setCourseDifficultyGateway(new CourseNotFoundCourseDifficultyGatewayStub());
        $this->executeUseCase(new GetCourseDifficultyRequestDTO(self::NON_EXISTING_COURSE_ID));
    }

    private function executeUseCase($request)
    {
        $this->response = $this->useCase->execute($request);
    }

    /**
     * @test
     */
    public function WithoutDifficulty_ReturnNoDifficulty()
    {
        $this->useCase->setCourseDifficultyGateway(
            new WithoutDifficultyCourseDifficultyGatewayStub()
        );
        $this->executeUseCase(
            new GetCourseDifficultyRequestDTO(self::WITHOUT_DIFFICULTY_COURSE_ID)
        );
        $this->assertDifficulty('');
    }

    private function assertDifficulty($expectedDifficulty)
    {
        $this->assertEquals($expectedDifficulty, $this->response->getDifficulty());
    }

    /**
     * @test
     */
    public function ReturnDifficulty()
    {
        $this->useCase->setCourseDifficultyGateway(new CourseDifficultyGatewaySpy());
        $this->executeUseCase(new GetCourseDifficultyRequestDTO(self::EASY_COURSE_ID));
        $this->assertDifficulty(Difficulty::EASY);
    }

    protected function setup()
    {
        $this->useCase = new GetCourseDifficulty();
    }
}
