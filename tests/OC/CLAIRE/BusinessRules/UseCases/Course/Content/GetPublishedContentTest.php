<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Content;

use OC\CLAIRE\BusinessRules\Requestors\Course\Content\GetPublishedContentRequest;
use OC\CLAIRE\BusinessRules\Responders\Course\Content\GetContentResponse;
use OC\CLAIRE\BusinessRules\UseCases\Course\Content\DTO\GetPublishedContentRequestDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetPublishedContentTest extends \PHPUnit_Framework_TestCase
{
    const COURSE_CONTENT_1 = '<h2 id="10">Test</h2>';

    const COURSE_IDENTIFIER_1 = 1;

    /**
     * @var GetPublishedContent
     */
    private $useCase;

    /**
     * @var GetPublishedContentRequest
     */
    private $request;

    /**
     * @var GetContentResponse
     */
    private $response;

    /**
     * @test
     */
    public function Execute_Returns_content()
    {
        $this->request = new GetPublishedContentRequestDTO(self::COURSE_IDENTIFIER_1);
        $this->executeUseCase();
        $this->assertEquals(self::COURSE_CONTENT_1, $this->response->getContent());
    }

    private function executeUseCase()
    {
        $this->response = $this->useCase->execute($this->request);
    }

    protected function setup()
    {
        $this->useCase = new GetPublishedContent();
        $gateway = $this->getMockBuilder(
            'OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseContentGateway'
        )
            ->disableOriginalConstructor()->getMock();
        $gateway->expects($this->any())
            ->method('findPublished')
            ->with(self::COURSE_IDENTIFIER_1)
            ->will($this->returnValue(self::COURSE_CONTENT_1));

        $this->useCase->setCourseContentGateway($gateway);
    }
}
