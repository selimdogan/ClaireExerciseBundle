<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Workflow;

use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseNotFoundCourseGatewayStub;
use OC\CLAIRE\BusinessRules\Requestors\Course\Workflow\ChangeCourseStatusRequest;

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
     * @expectedException \OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseNotFoundException
     */
    public function Execute_WithNonExistingCourse_ThrowException()
    {
        $this->useCase->setCourseGateway(new CourseNotFoundCourseGatewayStub());
        $this->executeUseCase();
    }
}
