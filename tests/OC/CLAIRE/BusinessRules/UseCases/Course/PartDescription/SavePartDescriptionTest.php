<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\PartDescription;

use OC\CLAIRE\BusinessRules\Gateways\Course\Part\CourseNotFoundPartGateway;
use OC\CLAIRE\BusinessRules\Gateways\Course\Part\PartGatewaySpy;
use OC\CLAIRE\BusinessRules\Gateways\Course\Part\PartNotFoundPartGatewayStub;
use OC\CLAIRE\BusinessRules\UseCases\Course\PartDescription\DTO\SavePartDescriptionRequestDTO;
use SimpleIT\ClaireAppBundle\Entity\Course\Part\PartFactoryImpl;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class SavePartDescriptionTest extends \PHPUnit_Framework_TestCase
{
    const NON_EXISTING_COURSE_ID = 9;

    const NON_EXISTING_PART_ID = 99;

    const COURSE_1_ID = 1;

    const EMPTY_DESCRIPTION_PART_ID = 10;

    const PART_1_ID = 11;

    const PART_1_DESCRIPTION = 'Part 1 description';

    /**
     * @var SavePartDescription
     */
    private $useCase;

    /**
     * @test
     * @expectedException \OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException
     */
    public function NonExistingCourse_ThrowException()
    {
        $this->useCase->setPartGateway(new CourseNotFoundPartGateway());
        $this->executeUseCase(
            self::NON_EXISTING_COURSE_ID,
            self::NON_EXISTING_PART_ID,
            self::PART_1_DESCRIPTION
        );
    }

    private function executeUseCase($courseId, $partId, $description)
    {
        $this->useCase->execute(
            new SavePartDescriptionRequestDTO($courseId, $partId, $description)
        );
    }

    /**
     * @test
     * @expectedException \OC\CLAIRE\BusinessRules\Exceptions\Course\Part\PartNotFoundException
     */
    public function NonExistingPart_ThrowException()
    {
        $this->useCase->setPartGateway(new PartNotFoundPartGatewayStub());
        $this->executeUseCase(
            self::COURSE_1_ID,
            self::NON_EXISTING_PART_ID,
            self::PART_1_DESCRIPTION
        );
    }

    /**
     * @test
     */
    public function SavePartDescription_Save()
    {
        $gateway = new PartGatewaySpy();
        $this->useCase->setPartGateway($gateway);
        $this->executeUseCase(self::COURSE_1_ID, self::PART_1_ID, self::PART_1_DESCRIPTION);
        $this->assertEquals($gateway->courseId, self::COURSE_1_ID);
        $this->assertEquals($gateway->partId, self::PART_1_ID);
        $this->assertEquals($gateway->part->getDescription(), self::PART_1_DESCRIPTION);
    }

    protected function setup()
    {
        $this->useCase = new SavePartDescription();
        $this->useCase->setPartFactory(new PartFactoryImpl());
    }
}
