<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\CourseContent;

use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseContentGatewaySpy;
use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseNotFoundCourseContentGatewayStub;
use OC\CLAIRE\BusinessRules\Requestors\Course\CourseContent\GetCourseContentRequest;
use OC\CLAIRE\BusinessRules\Responders\Course\CourseContent\GetCourseContentResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
abstract class GetCourseContentTest extends \PHPUnit_Framework_TestCase
{
    const COURSE_ID_1 = 1;

    const COURSE_CONTENT_1 = '<h2 id="10">Course Content 1</h2>';

    /**
     * @var GetCourseContent
     */
    protected $useCase;

    /**
     * @var GetCourseContentRequest
     */
    protected $request;

    /**
     * @var GetCourseContentResponse
     */
    protected $response;

    /**
     * @test
     * @expectedException \OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException
     */
    public function NonExistingCourse_ThrowException()
    {
        $this->useCase->setCourseContentGateway(new CourseNotFoundCourseContentGatewayStub());
        $this->executeUseCase();
    }

    protected function executeUseCase()
    {
        $this->response = $this->useCase->execute($this->request);
    }

    protected function setUp()
    {
        $this->useCase->setCourseContentGateway(new CourseContentGatewaySpy());
    }

}
