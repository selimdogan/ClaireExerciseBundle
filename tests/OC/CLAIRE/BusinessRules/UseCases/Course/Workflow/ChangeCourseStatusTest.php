<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Workflow;

use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseGatewaySpy;
use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseNotFoundCourseGatewayStub;
use OC\CLAIRE\BusinessRules\Requestors\Course\Workflow\ChangeCourseStatusRequest;
use OC\CLAIRE\BusinessRules\UseCases\Course\Workflow\DTO\ChangeCourseStatusRequestDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
abstract class ChangeCourseStatusTest extends \PHPUnit_Framework_TestCase
{
    const COURSE_ID = 1;

    /**
     * @var ChangeCourseStatus
     */
    protected $useCase;

    /**
     * @var ChangeCourseStatusRequest
     */
    protected $request;

    /**
     * @var CourseGatewaySpy
     */
    protected $courseGateway;

    /**
     * @test
     */
    public function Execute()
    {
        $this->executeUseCase();
        $this->assertEquals(self::COURSE_ID, $this->courseGateway->courseId);
    }

    protected function executeUseCase()
    {
        $this->useCase->execute($this->request);
    }

    /**
     * @test
     * @expectedException \OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException
     */
    public function Execute_WithNonExistingCourse_ThrowException()
    {
        $this->useCase->setCourseGateway(new CourseNotFoundCourseGatewayStub());
        $this->executeUseCase();
    }

    protected function setUp()
    {
        $this->courseGateway = new CourseGatewaySpy();
        $this->useCase->setCourseGateway($this->courseGateway);
        $this->request = new ChangeCourseStatusRequestDTO(self::COURSE_ID);
    }

}
