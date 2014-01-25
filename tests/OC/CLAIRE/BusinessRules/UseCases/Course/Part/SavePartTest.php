<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Part;

use OC\CLAIRE\BusinessRules\Entities\Course\Course\Difficulty;
use OC\CLAIRE\BusinessRules\Entities\Course\Course\Duration;
use OC\CLAIRE\BusinessRules\Gateways\Course\Part\CourseNotFoundPartGateway;
use OC\CLAIRE\BusinessRules\Gateways\Course\Part\PartGatewaySpy;
use OC\CLAIRE\BusinessRules\Gateways\Course\Part\PartNotFoundPartGatewayStub;
use OC\CLAIRE\BusinessRules\UseCases\Course\Part\DTO\SavePartRequestBuilderImpl;
use SimpleIT\ApiResourcesBundle\Course\PartResource;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class SavePartTest extends \PHPUnit_Framework_TestCase
{
    const NON_EXISTING_COURSE_ID = 9;

    const NON_EXISTING_PART_ID = 99;

    const COURSE_1_ID = 1;

    const EMPTY_DESCRIPTION_PART_ID = 10;

    const PART_1_ID = 11;

    const PART_1_DESCRIPTION = 'Part 1 description';

    /**
     * @var SavePart
     */
    private $useCase;

    /**
     * @var PartGatewaySpy
     */
    private $partGatewaySpy;

    /**
     * @test
     * @expectedException \OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException
     */
    public function NonExistingCourse_ThrowException()
    {
        $this->useCase->setPartGateway(new CourseNotFoundPartGateway());
        $this->useCase->execute(
            SavePartRequestBuilderImpl::create()
                ->part(self::NON_EXISTING_PART_ID)
                ->fromCourse(self::NON_EXISTING_COURSE_ID)
                ->build()
        );
    }

    /**
     * @test
     * @expectedException \OC\CLAIRE\BusinessRules\Exceptions\Course\Part\PartNotFoundException
     */
    public function NonExistingPart_ThrowException()
    {
        $this->useCase->setPartGateway(new PartNotFoundPartGatewayStub());
        $this->useCase->execute(
            SavePartRequestBuilderImpl::create()
                ->part(self::NON_EXISTING_PART_ID)
                ->fromCourse(self::COURSE_1_ID)
                ->build()
        );
    }

    /**
     * @test
     */
    public function SavePartDescription()
    {
        $this->useCase->execute(
            SavePartRequestBuilderImpl::create()
                ->part(self::PART_1_ID)
                ->fromCourse(self::COURSE_1_ID)
                ->withDescription(self::PART_1_DESCRIPTION)
                ->build()
        );
        $expectedResource = new PartResource();
        $expectedResource->setDescription(self::PART_1_DESCRIPTION);
        $this->assertPart($expectedResource);
    }

    protected function assertPart($expectedResource)
    {
        $this->assertEquals(self::COURSE_1_ID, $this->partGatewaySpy->courseId);
        $this->assertEquals(self::PART_1_ID, $this->partGatewaySpy->partId);
        $this->assertEquals($expectedResource, $this->partGatewaySpy->part);
    }

    /**
     * @test
     */
    public function SavePartDifficulty()
    {
        $this->useCase->execute(
            SavePartRequestBuilderImpl::create()
                ->part(self::PART_1_ID)
                ->fromCourse(self::COURSE_1_ID)
                ->withDifficulty(Difficulty::EASY)
                ->build()
        );
        $expectedResource = new PartResource();
        $expectedResource->setDifficulty(Difficulty::EASY);
        $this->assertPart($expectedResource);
    }

    /**
     * @test
     */
    public function SavePartDuration()
    {
        $this->useCase->execute(
            SavePartRequestBuilderImpl::create()
                ->part(self::PART_1_ID)
                ->fromCourse(self::COURSE_1_ID)
                ->withDuration(Duration::P1D)
                ->build()
        );
        $expectedResource = new PartResource();
        $expectedResource->setDuration(Duration::P1D);
        $this->assertPart($expectedResource);
    }

    protected function setUp()
    {
        $this->partGatewaySpy = new PartGatewaySpy();
        $this->useCase = new SavePart();
        $this->useCase->setPartGateway($this->partGatewaySpy);
    }

}
