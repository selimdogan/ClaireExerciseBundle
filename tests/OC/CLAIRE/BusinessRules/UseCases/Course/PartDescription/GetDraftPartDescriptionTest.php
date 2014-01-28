<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\PartDescription;

use OC\CLAIRE\BusinessRules\Entities\Course\Part\PartStub;
use OC\CLAIRE\BusinessRules\Gateways\Course\Part\CourseNotFoundPartGateway;
use OC\CLAIRE\BusinessRules\Gateways\Course\Part\EmptyPartGatewayStub;
use OC\CLAIRE\BusinessRules\Gateways\Course\Part\PartGatewaySpy;
use OC\CLAIRE\BusinessRules\Gateways\Course\Part\PartNotFoundPartGatewayStub;
use OC\CLAIRE\BusinessRules\Responders\Course\PartDescription\GetDraftPartDescriptionResponse;
use OC\CLAIRE\BusinessRules\UseCases\Course\PartDescription\DTO\GetDraftPartDescriptionRequestDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftPartDescriptionTest extends \PHPUnit_Framework_TestCase
{
    const NON_EXISTING_COURSE_ID = 9;

    const NON_EXISTING_PART_ID = 99;

    const COURSE_1_ID = 1;

    const EMPTY_DESCRIPTION_PART_ID = 10;

    const PART_1_ID = 11;

    /**
     * @var GetDraftPartDescription
     */
    private $useCase;

    /**
     * @var GetDraftPartDescriptionResponse
     */
    private $response;

    /**
     * @test
     * @expectedException \OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException
     */
    public function NonExistingCourse_ThrowException()
    {
        $this->useCase->setPartGateway(new CourseNotFoundPartGateway());
        $this->executeUseCase(self::NON_EXISTING_COURSE_ID, self::NON_EXISTING_PART_ID);
    }

    private function executeUseCase($courseId, $partId)
    {
        $this->response = $this->useCase->execute(
            new GetDraftPartDescriptionRequestDTO($courseId, $partId)
        );
    }

    /**
     * @test
     * @expectedException \OC\CLAIRE\BusinessRules\Exceptions\Course\Part\PartNotFoundException
     */
    public function NonExistingPart_ThrowException()
    {
        $this->useCase->setPartGateway(new PartNotFoundPartGatewayStub());
        $this->executeUseCase(self::COURSE_1_ID, self::NON_EXISTING_PART_ID);
    }

    /**
     * @test
     */
    public function EmptyPartDescription_ReturnEmptyPartDescription()
    {
        $this->useCase->setPartGateway(new EmptyPartGatewayStub());
        $this->executeUseCase(self::COURSE_1_ID, self::EMPTY_DESCRIPTION_PART_ID);
        $this->assertEmpty($this->response->getDescription());

    }

    /**
     * @test
     */
    public function PartWithDescription_ReturnDescription()
    {
        $this->useCase->setPartGateway(new PartGatewaySpy());
        $this->executeUseCase(self::COURSE_1_ID, self::PART_1_ID);
        $this->assertEquals($this->response->getDescription(), PartStub::DESCRIPTION);
    }

    protected function setup()
    {
        $this->useCase = new GetDraftPartDescription();
    }
}
