<?php

namespace OC\BusinessRules\UseCases\Course\Content;

use OC\BusinessRules\Requestors\Course\Content\GetWaitingForPublicationContentRequest;
use OC\BusinessRules\Responders\Course\Content\GetContentResponse;
use OC\BusinessRules\UseCases\Course\Content\DTO\GetWaitingForPublicationContentRequestDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetWaitingForPublicationTest extends \PHPUnit_Framework_TestCase
{
    const COURSE_CONTENT_1 = '<h2 id="10">Test</h2>';

    const COURSE_ID_1 = 1;

    /**
     * @var GetWaitingForPublicationContent
     */
    private $useCase;

    /**
     * @var GetWaitingForPublicationContentRequest
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
        $this->request = new GetWaitingForPublicationContentRequestDTO(self::COURSE_ID_1);
        $this->executeUseCase();
        $this->assertEquals(self::COURSE_CONTENT_1, $this->response->getContent());
    }

    private function executeUseCase()
    {
        $this->response = $this->useCase->execute($this->request);
    }

    protected function setup()
    {
        $this->useCase = new GetWaitingForPublicationContent();
        $gateway = $this->getMockBuilder(
            'OC\BusinessRules\Gateways\Course\Course\CourseContentGateway'
        )
            ->disableOriginalConstructor()->getMock();
        $gateway->expects($this->any())
            ->method('findWaitingForPublication')
            ->with(self::COURSE_ID_1)
            ->will($this->returnValue(self::COURSE_CONTENT_1));

        $this->useCase->setCourseContentGateway($gateway);
    }
}
