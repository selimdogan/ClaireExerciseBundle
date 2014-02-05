<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Course;

use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseGatewayStub;
use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseNotFoundCourseGatewayStub;
use OC\CLAIRE\BusinessRules\Requestors\Course\Course\GetCourseStatusesRequest;
use OC\CLAIRE\BusinessRules\Responders\Course\Course\GetCourseStatusesResponse;
use OC\CLAIRE\BusinessRules\UseCases\Course\Course\DTO\GetCourseStatusesRequestDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetCourseStatusesTest extends \PHPUnit_Framework_TestCase
{
    const NON_EXISTING_COURSE_IDENTIFIER = 9;

    const COURSE_1_IDENTIFIER = 1;

    /**
     * @var GetCourseStatuses
     */
    private $useCase;

    /**
     * @var GetCourseStatusesRequest
     */
    private $request;

    /**
     * @var GetCourseStatusesResponse
     */
    private $response;

    /**
     * @test
     * @expectedException \OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException
     */
    public function NonExistingCourse_ThrowException()
    {
        $this->request = new GetCourseStatusesRequestDTO(self::NON_EXISTING_COURSE_IDENTIFIER);
        $this->useCase->setCourseGateway(new CourseNotFoundCourseGatewayStub());
        $this->executeUseCase();
    }

    protected function executeUseCase()
    {
        $this->response = $this->useCase->execute($this->request);
    }

    /**
     * @test
     */
    public function AllStatus_ReturnAll()
    {
        $this->request = new GetCourseStatusesRequestDTO(self::COURSE_1_IDENTIFIER);
        $this->useCase->setCourseGateway(new CourseGatewayStub());
        $this->executeUseCase();
        $courses = $this->response->getCourses();
        $this->assertCount(3, $courses);
    }

    protected function setUp()
    {
        $this->useCase = new GetCourseStatuses();
    }

}
