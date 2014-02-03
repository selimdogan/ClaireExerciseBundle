<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\PartContent;

use OC\CLAIRE\BusinessRules\Gateways\Course\Part\CourseNotFoundPartContentGateway;
use OC\CLAIRE\BusinessRules\Gateways\Course\Part\PartContentGatewaySpy;
use OC\CLAIRE\BusinessRules\Gateways\Course\Part\PartNotFoundPartContentGatewayStub;
use OC\CLAIRE\BusinessRules\Responders\Course\PartContent\SavePartContentResponse;
use OC\CLAIRE\BusinessRules\UseCases\Course\PartContent\DTO\SavePartContentRequestDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class SavePartContentTest extends \PHPUnit_Framework_TestCase
{
    const NON_EXISTING_COURSE_ID = 9;

    const NON_EXISTING_PART_ID = 99;

    const COURSE_1_ID = 1;

    const PART_1_ID = 11;

    const INPUT_CONTENT_1 = '<h2>Test</h2>';

    /**
     * @var SavePartContent
     */
    protected $useCase;

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
        $this->useCase->execute(
            new SavePartContentRequestDTO(self::NON_EXISTING_COURSE_ID, self::NON_EXISTING_PART_ID, self::INPUT_CONTENT_1)
        );
    }

    /**
     * @test
     * @expectedException \OC\CLAIRE\BusinessRules\Exceptions\Course\Part\PartNotFoundException
     */
    public function NonExistingPart_ThrowException()
    {
        $this->useCase->setPartContentGateway(new PartNotFoundPartContentGatewayStub());
        $this->useCase->execute(
            new SavePartContentRequestDTO(self::COURSE_1_ID, self::NON_EXISTING_PART_ID, self::INPUT_CONTENT_1)
        );
    }

    /**
     * @test
     */
    public function SaveContent_ReturnSavedContent()
    {
        $partContentGateway = new PartContentGatewaySpy();
        $this->useCase->setPartContentGateway($partContentGateway);
        /** @var SavePartContentResponse $response */
        $response = $this->useCase->execute(
            new SavePartContentRequestDTO(self::COURSE_1_ID, self::PART_1_ID, self::INPUT_CONTENT_1)
        );

        $this->assertEquals(self::COURSE_1_ID, $partContentGateway->courseId);
        $this->assertEquals(self::PART_1_ID, $partContentGateway->partId);
        $this->assertEquals(self::INPUT_CONTENT_1, $partContentGateway->content);
        $this->assertEquals(PartContentGatewaySpy::UPDATED_CONTENT, $response->getContent());

    }

    protected function setUp()
    {
        $this->useCase = new SavePartContent();
    }
}
