<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Course;

use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseNotFoundCourseGatewayStub;
use OC\CLAIRE\BusinessRules\Requestors\Course\Course\SaveCourseRequest;
use OC\CLAIRE\BusinessRules\UseCases\Course\Course\DTO\SaveCourseRequestDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class SaveCourseTest extends \PHPUnit_Framework_TestCase
{
    const COURSE_ID = 1;

    const COURSE_SLUG = 'course-title-1';

    /**
     * @var GetCourse
     */
    protected $useCase;

    /**
     * @var SaveCourseRequest
     */
    protected $request;

    /**
     * @var GetCourseResponse
     */
    protected $response;

    /**
     * @test
     * @expectedException \OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException
     */
    public function NonExistingCourse_ThrowException()
    {
        $this->useCase->setCourseGateway(new CourseNotFoundCourseGatewayStub());
        $this->useCase->execute(new SaveCourseRequestDTO());
    }

    protected function setUp()
    {
        $this->useCase = new SaveCourse();
    }

}
