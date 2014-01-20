<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\CourseToc;

use OC\CLAIRE\BusinessRules\Gateways\Course\Toc\CourseNotFoundTocByCourseGatewayStub;
use OC\CLAIRE\BusinessRules\Requestors\Course\CourseToc\GetCourseTocRequest;
use OC\CLAIRE\BusinessRules\Responders\Course\CourseToc\GetCourseTocResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
abstract class GetCourseTocTest extends \PHPUnit_Framework_TestCase
{
    const COURSE_ID = 1;

    /**
     * @var GetCourseToc
     */
    protected $useCase;

    /**
     * @var GetCourseTocRequest
     */
    protected $request;

    /**
     * @var GetCourseTocResponse
     */
    protected $response;

    /**
     * @test
     * @expectedException \OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException
     */
    public function NonExistingCourse_ThrowException()
    {
        $this->useCase->setTocByCourseGateway(new CourseNotFoundTocByCourseGatewayStub());
        $this->executeUseCase();
    }

    protected function executeUseCase()
    {
        /** @var GetCourseTocResponse $response */
        $this->response = $this->useCase->execute($this->request);
    }
}
