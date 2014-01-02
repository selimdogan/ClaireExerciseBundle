<?php

namespace OC\BusinessRules\UseCases\Course\Course;

use OC\BusinessRules\Entities\Course\CourseStub;
use OC\BusinessRules\Gateways\Course\Course\CourseNotFoundCourseGatewayStub;
use OC\BusinessRules\Responders\Course\Course\GetCourseResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
abstract class GetCourseTest extends \PHPUnit_Framework_TestCase
{

    const COURSE_ID = 1;

    const COURSE_SLUG = 'course-title-1';

    /**
     * @var GetCourse
     */
    protected $useCase;

    /**
     * @var \OC\BusinessRules\Requestors\Course\Course\GetCourseRequest
     */
    protected $request;

    /**
     * @var GetCourseResponse
     */
    protected $response;

    /**
     * @test
     * @expectedException \OC\BusinessRules\Gateways\Course\Course\CourseNotFoundException
     */
    public function NonExistingCourse_ThrowException()
    {
        $this->useCase->setCourseGateway(new CourseNotFoundCourseGatewayStub());
        $this->executeUseCase();
    }

    protected function executeUseCase()
    {
        /** @var GetCourseResponse $response */
        $this->response = $this->useCase->execute($this->request);
    }

    protected function assertCourse()
    {
        $this->assertEquals(
            new \DateTime(CourseStub::COURSE_CREATED_AT),
            $this->response->getCreatedAt()
        );
        $this->assertEquals(
            \OC\BusinessRules\Entities\Course\CourseStub::COURSE_DISPLAY_LEVEL,
            $this->response->getDisplayLevel()
        );
        $this->assertEquals(CourseStub::COURSE_ID, $this->response->getId());
        $this->assertEquals(
            \OC\BusinessRules\Entities\Course\CourseStub::COURSE_SLUG, $this->response->getSlug());
        $this->assertEquals(CourseStub::COURSE_TITLE, $this->response->getTitle());
        $this->assertEquals(
            new \DateTime(CourseStub::COURSE_UPDATED_AT),
            $this->response->getUpdatedAt()
        );
    }
}
