<?php

namespace SimpleIT\ClaireAppBundle\UseCases\Course\Content;

use SimpleIT\ClaireAppBundle\Requestors\Course\Content\GetDraftContentRequest;
use SimpleIT\ClaireAppBundle\UseCases\Course\Content\DTO\GetDraftContentRequestDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftContentTest extends \PHPUnit_Framework_TestCase
{
    const COURSE_CONTENT_1 = '<h2 id="10">Test</h2>';

    const COURSE_ID_1 = 1;

    private $response;

    /**
     * @var GetDraftContent
     */
    private $useCase;

    /**
     * @var GetDraftContentRequest
     */
    private $request;

    /**
     * @test
     */
    public function Execute_Returns_content()
    {
        $this->request = new GetDraftContentRequestDTO(self::COURSE_ID_1);
        $this->executeUseCase();
        $this->assertEquals(self::COURSE_CONTENT_1, $this->response->getContent());
    }

    private function executeUseCase()
    {
        $this->response = $this->useCase->execute($this->request);
    }

    protected function setup()
    {
        $this->useCase = new GetDraftContent();
        $gateway = $this->getMockBuilder(
            'SimpleIT\ClaireAppBundle\Gateways\Course\Course\CourseContentGateway'
        )
            ->disableOriginalConstructor()->getMock();
        $gateway->expects($this->any())
            ->method('findDraft')
            ->with(self::COURSE_ID_1)
            ->will($this->returnValue(self::COURSE_CONTENT_1));

        $this->useCase->setCourseContentGateway($gateway);
    }
}
