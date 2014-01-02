<?php

namespace OC\BusinessRules\UseCases\Course\Workflow;

use OC\BusinessRules\Gateways\Course\Course\CourseNotFoundCourseGatewayStub;
use OC\BusinessRules\Requestors\Course\Workflow\ChangeCourseStatusRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
abstract class ChangeCourseStatusTest extends \PHPUnit_Framework_TestCase
{
    protected $execute;

    /**
     * @var ChangeCourseStatus
     */
    protected $useCase;

    /**
     * @var ChangeCourseStatusRequest
     */
    protected $request;

    /**
     * @test
     */
    public function Execute()
    {
        $this->executeUseCase();
        $this->assertNotNull($this->execute);
    }

    protected function executeUseCase()
    {
        $this->execute = $this->useCase->execute($this->request);
    }

    /**
     * @test
     * @expectedException \OC\BusinessRules\Gateways\Course\Course\CourseNotFoundException
     */
    public function Execute_WithNonExistingCourse_ThrowException()
    {
        $this->useCase->setCourseGateway(new CourseNotFoundCourseGatewayStub());
        $this->executeUseCase();
    }
}
