<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\CourseContent;

use OC\CLAIRE\BusinessRules\UseCases\Course\CourseContent\DTO\SaveCourseContentRequestDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class SaveContentTest extends \PHPUnit_Framework_TestCase
{
    const COURSE_ID_1 = 1;

    const INPUT_CONTENT_1 = '<h2>Test</h2>';

    const EXPECTED_CONTENT_1 = '<h2 id="r-1">Test</h2>';

    /**
     * @var SaveCourseContent
     */
    private $useCase;

    /**
     * @test
     */
    public function Execute_ReturnSavedContents()
    {
        $request = new SaveCourseContentRequestDTO(self::COURSE_ID_1, self::INPUT_CONTENT_1);

        $response = $this->useCase->execute($request);
        $this->assertEquals(self::EXPECTED_CONTENT_1, $response->getContent());
    }

    protected function setup()
    {
        $this->useCase = new SaveCourseContent();
        $gateway = $this->getMockBuilder(
            'OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseContentGateway'
        )->disableOriginalConstructor()->getMock();

        $gateway->expects($this->any())
            ->method('update')
            ->with($this->equalTo(self::COURSE_ID_1), $this->equalTo(self::INPUT_CONTENT_1))
            ->will($this->returnValue(self::EXPECTED_CONTENT_1));

        $this->useCase->setCourseContentGateway($gateway);
    }
}
