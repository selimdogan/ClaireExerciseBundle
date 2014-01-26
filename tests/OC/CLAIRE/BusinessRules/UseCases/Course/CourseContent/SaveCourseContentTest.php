<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\CourseContent;

use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseContentGatewaySpy;
use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseNotFoundCourseContentGatewayStub;
use OC\CLAIRE\BusinessRules\Responders\Course\CourseContent\SaveCourseContentResponse;
use OC\CLAIRE\BusinessRules\UseCases\Course\CourseContent\DTO\SaveCourseContentRequestDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class SaveCourseContentTest extends \PHPUnit_Framework_TestCase
{
    const NON_EXISTING_COURSE_ID = 9;

    const COURSE_1_ID = 1;

    const INPUT_CONTENT_1 = '<h2>Test</h2>';

    /**
     * @var SaveCourseContent
     */
    protected $useCase;

    /**
     * @var CourseContentGatewaySpy
     */
    protected $partContentGatewaySpy;

    /**
     * @test
     * @expectedException \OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException
     */
    public function NonExistingCourse_ThrowException()
    {
        $this->useCase->setCourseContentGateway(new CourseNotFoundCourseContentGatewayStub());
        $this->useCase->execute(
            new SaveCourseContentRequestDTO(self::NON_EXISTING_COURSE_ID, self::INPUT_CONTENT_1)
        );
    }

    /**
     * @test
     */
    public function SaveContent_ReturnSavedContent()
    {
        $partContentGateway = new CourseContentGatewaySpy();
        $this->useCase->setCourseContentGateway($partContentGateway);
        /** @var SaveCourseContentResponse $response */
        $response = $this->useCase->execute(
            new SaveCourseContentRequestDTO(self::COURSE_1_ID, self::INPUT_CONTENT_1)
        );

        $this->assertEquals(self::COURSE_1_ID, $partContentGateway->courseId);
        $this->assertEquals(self::INPUT_CONTENT_1, $partContentGateway->content);
        $this->assertEquals(CourseContentGatewaySpy::UPDATED_CONTENT, $response->getContent());

    }

    protected function setUp()
    {
        $this->useCase = new SaveCourseContent();
    }
}
