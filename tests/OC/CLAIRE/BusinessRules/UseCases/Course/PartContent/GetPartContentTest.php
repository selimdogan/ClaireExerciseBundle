<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\PartContent;

use OC\CLAIRE\BusinessRules\Gateways\Course\Part\CourseNotFoundPartContentGateway;
use OC\CLAIRE\BusinessRules\Gateways\Course\Part\PartContentGatewaySpy;
use OC\CLAIRE\BusinessRules\Gateways\Course\Part\PartNotFoundPartContentGatewayStub;
use OC\CLAIRE\BusinessRules\Requestors\Course\PartContent\GetPartContentRequest;
use OC\CLAIRE\BusinessRules\Responders\Course\PartContent\GetPartContentResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
abstract class GetPartContentTest extends \PHPUnit_Framework_TestCase
{
    const NON_EXISTING_COURSE_ID = 9;

    const NON_EXISTING_PART_ID = 99;

    const COURSE_1_ID = 1;

    const PART_1_ID = 11;

    /**
     * @var GetPartContent
     */
    protected $useCase;

    /**
     * @var GetPartContentRequest
     */
    protected $request;

    /**
     * @var GetPartContentResponse
     */
    protected $response;

    /**
     * @var PartContentGatewaySpy
     */
    protected $partContentGatewaySpy;

    /**
     * @test
     * @expectedException \OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException
     */
    public function NonExistingCourse_ThrowException()
    {
        $this->useCase->setPartContentGateway(new CourseNotFoundPartContentGateway());
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
        $this->useCase->setPartContentGateway(new PartNotFoundPartContentGatewayStub());
        $this->executeUseCase($this->request);
    }

    protected function setUp()
    {
        $this->partContentGatewaySpy = new PartContentGatewaySpy();
        $this->useCase->setPartContentGateway($this->partContentGatewaySpy);
    }
}
