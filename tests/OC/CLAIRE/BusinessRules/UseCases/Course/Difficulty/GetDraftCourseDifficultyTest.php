<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Difficulty;

use OC\CLAIRE\BusinessRules\Entities\Difficulty\Difficulty;
use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseGatewaySpy;
use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseNotFoundCourseGatewayStub;
use OC\CLAIRE\BusinessRules\Gateways\Course\Course\EmptyCourseGatewayStub;
use OC\CLAIRE\BusinessRules\Responders\Course\Difficulty\GetCourseDifficultyResponse;
use OC\CLAIRE\BusinessRules\UseCases\Course\Difficulty\DTO\GetDraftCourseDifficultyRequestDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftCourseDifficultyTest extends \PHPUnit_Framework_TestCase
{
    const WITHOUT_DIFFICULTY_COURSE_ID = 998;

    const EASY_COURSE_ID = 1;

    const NON_EXISTING_COURSE_ID = 999;

    /**
     * @var GetCourseDifficultyResponse
     */
    private $response;

    /**
     * @var GetDraftCourseDifficulty
     */
    private $useCase;

    /**
     * @test
     * @expectedException \OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException
     */
    public function NonExistingCourse_ThrowException()
    {
        $this->useCase->setCourseGateway(new CourseNotFoundCourseGatewayStub());
        $this->executeUseCase(new GetDraftCourseDifficultyRequestDTO(self::NON_EXISTING_COURSE_ID));
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
        $this->useCase->setCourseGateway(new EmptyCourseGatewayStub());
        $this->executeUseCase(
            new GetDraftCourseDifficultyRequestDTO(self::WITHOUT_DIFFICULTY_COURSE_ID)
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
        $this->useCase->setCourseGateway(new CourseGatewaySpy());
        $this->executeUseCase(new GetDraftCourseDifficultyRequestDTO(self::EASY_COURSE_ID));
        $this->assertDifficulty(Difficulty::EASY);
    }

    protected function setup()
    {
        $this->useCase = new GetDraftCourseDifficulty();
    }
}
