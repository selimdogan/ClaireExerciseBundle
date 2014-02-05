<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Part;

use OC\CLAIRE\BusinessRules\Entities\Course\Part\PartStub;
use OC\CLAIRE\BusinessRules\Gateways\Course\Part\CourseNotFoundPartGateway;
use OC\CLAIRE\BusinessRules\Gateways\Course\Part\PartGatewaySpy;
use OC\CLAIRE\BusinessRules\Gateways\Course\Part\PartNotFoundPartGatewayStub;
use OC\CLAIRE\BusinessRules\Requestors\Course\Part\GetPartRequest;
use OC\CLAIRE\BusinessRules\Responders\Course\Part\GetPartResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
abstract class AGetPartTest extends \PHPUnit_Framework_TestCase
{
    const NON_EXISTING_COURSE_ID = 9;

    const NON_EXISTING_PART_ID = 99;

    const COURSE_1_ID = 1;

    const PART_1_ID = 11;

    /**
     * @var GetPart
     */
    protected $useCase;

    /**
     * @var GetPartRequest
     */
    protected $request;

    /**
     * @var GetPartResponse
     */
    protected $response;

    /**
     * @var PartGatewaySpy
     */
    protected $partGatewaySpy;

    /**
     * @test
     * @expectedException \OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException
     */
    public function NonExistingCourse_ThrowException()
    {
        $this->useCase->setPartGateway(new CourseNotFoundPartGateway());
        $this->executeUseCase();
    }

    protected function executeUseCase()
    {
        $this->response = $this->useCase->execute($this->request);
    }

    /**
     * @test
     * @expectedException \OC\CLAIRE\BusinessRules\Exceptions\Course\Part\PartNotFoundException
     */
    public function NonExistingPart_ThrowException()
    {
        $this->useCase->setPartGateway(new PartNotFoundPartGatewayStub());
        $this->executeUseCase($this->request);
    }

    /**
     * @test
     */
    public function GetPart()
    {
        $this->executeUseCase();
        $this->assertPart();
    }

    protected function assertPart()
    {

        $this->assertEquals(new \DateTime(PartStub::CREATED_AT), $this->response->getCreatedAt());
        $this->assertEquals(PartStub::DESCRIPTION, $this->response->getDescription());
        $this->assertEquals(PartStub::DIFFICULTY, $this->response->getDifficulty());
        $this->assertEquals(PartStub::DURATION, $this->response->getDuration());
        $this->assertEquals(PartStub::ID, $this->response->getId());
        $this->assertEquals(PartStub::SLUG, $this->response->getSlug());
        $this->assertEquals(PartStub::SUBTYPE, $this->response->getSubtype());
        $this->assertEquals(PartStub::TITLE, $this->response->getTitle());
        $this->assertEquals(new \DateTime(PartStub::UPDATED_AT), $this->response->getUpdatedAt());
    }

    protected function setUp()
    {
        $this->partGatewaySpy = new PartGatewaySpy();
        $this->useCase->setPartGateway($this->partGatewaySpy);
    }
}
