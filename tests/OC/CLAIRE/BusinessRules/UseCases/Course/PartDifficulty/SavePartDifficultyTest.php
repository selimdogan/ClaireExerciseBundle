<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\PartDifficulty;

use OC\CLAIRE\BusinessRules\Entities\Course\Course\Difficulty;
use OC\CLAIRE\BusinessRules\Gateways\Course\Part\PartGatewaySpy;
use OC\CLAIRE\BusinessRules\Gateways\Course\Part\PartNotFoundGatewayStub;
use OC\CLAIRE\BusinessRules\UseCases\Course\PartDifficulty\DTO\SavePartDifficultyRequestDTO;
use SimpleIT\ClaireAppBundle\Entity\Course\Part\PartFactoryImpl;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class SavePartDifficultyTest extends \PHPUnit_Framework_TestCase
{
    const COURSE_1_ID = 1;

    const PART_1_ID = 1;

    const PART_1_DIFFICULTY = Difficulty::EASY;

    const NON_EXISTING_COURSE_ID = 999;

    const NON_EXISTING_PART_ID = 999;

    /**
     * @var SavePartDifficulty
     */
    private $useCase;

    /**
     * @var PartGatewaySpy
     */
    private $partGateway;

    /**
     * @test
     * @expectedException \OC\CLAIRE\BusinessRules\Exceptions\Course\Part\PartNotFoundException
     */
    public function NonExistingPart_ThrowException()
    {
        $this->useCase->setPartGateway(new PartNotFoundGatewayStub());
        $this->executeUseCase(
            new SavePartDifficultyRequestDTO(self::NON_EXISTING_COURSE_ID, self::NON_EXISTING_PART_ID, Difficulty::EASY)
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
        $this->partGateway = new PartGatewaySpy();
        $this->useCase->setPartGateway($this->partGateway);
        $this->executeUseCase(
            new SavePartDifficultyRequestDTO(self::COURSE_1_ID, self::PART_1_ID, self::PART_1_DIFFICULTY)
        );
        $this->assertDifficulty();
    }

    private function assertDifficulty()
    {
        $this->assertEquals(self::COURSE_1_ID, $this->partGateway->courseId);
        $this->assertEquals(self::PART_1_ID, $this->partGateway->partId);
        $this->assertEquals(
            self::PART_1_DIFFICULTY,
            $this->partGateway->part->getDifficulty()
        );
    }

    protected function setup()
    {
        $this->useCase = new SavePartDifficulty();
        $this->useCase->setPartFactory(new PartFactoryImpl());
    }
}
