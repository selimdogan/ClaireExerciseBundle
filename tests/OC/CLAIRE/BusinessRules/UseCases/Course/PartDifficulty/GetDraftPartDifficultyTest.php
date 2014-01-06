<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\PartDifficulty;

use OC\CLAIRE\BusinessRules\Entities\Difficulty\Difficulty;
use OC\CLAIRE\BusinessRules\Gateways\Course\Part\EmptyPartGatewayStub;
use OC\CLAIRE\BusinessRules\Gateways\Course\Part\PartGatewaySpy;
use OC\CLAIRE\BusinessRules\Gateways\Course\Part\PartNotFoundGatewayStub;
use OC\CLAIRE\BusinessRules\Responders\Course\PartDifficulty\GetDraftPartDifficultyResponse;
use OC\CLAIRE\BusinessRules\UseCases\Course\PartDifficulty\DTO\GetDraftPartDifficultyRequestDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftPartDifficultyTest extends \PHPUnit_Framework_TestCase
{
    const COURSE_1_ID = 1;

    const EASY_PART_ID_1 = 1;

    const WITHOUT_DIFFICULTY_PART_ID = 998;

    const NON_EXISTING_COURSE_ID = 999;

    const NON_EXISTING_PART_ID = 999;

    /**
     * @var GetDraftPartDifficulty
     */
    private $useCase;

    /**
     * @var GetDraftPartDifficultyResponse
     */
    private $response;

    /**
     * @test
     * @expectedException \OC\CLAIRE\BusinessRules\Exceptions\Course\Part\PartNotFoundException
     */
    public function NonExistingPart_ThrowException()
    {
        $this->useCase->setPartGateway(new PartNotFoundGatewayStub());
        $this->executeUseCase(
            new GetDraftPartDifficultyRequestDTO(self::NON_EXISTING_COURSE_ID, self::NON_EXISTING_PART_ID)
        );
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
        $this->useCase->setPartGateway(new EmptyPartGatewayStub());
        $this->executeUseCase(
            new GetDraftPartDifficultyRequestDTO(self::COURSE_1_ID, self::WITHOUT_DIFFICULTY_PART_ID)
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
        $this->useCase->setPartGateway(new PartGatewaySpy());
        $this->executeUseCase(
            new GetDraftPartDifficultyRequestDTO(self::COURSE_1_ID, self::EASY_PART_ID_1)
        );
        $this->assertDifficulty(Difficulty::EASY);
    }

    protected function setup()
    {
        $this->useCase = new GetDraftPartDifficulty();
    }
}
