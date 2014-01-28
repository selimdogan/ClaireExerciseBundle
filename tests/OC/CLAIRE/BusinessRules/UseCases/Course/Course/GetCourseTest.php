<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Course;

use OC\CLAIRE\BusinessRules\Entities\Course\Course\CourseStub;
use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseNotFoundCourseGatewayStub;
use OC\CLAIRE\BusinessRules\Requestors\Course\Course\GetCourseRequest;
use OC\CLAIRE\BusinessRules\Responders\Course\Course\GetCourseResponse;

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
     * @var GetCourseRequest
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
        $this->assertEquals(CourseStub::COURSE_DESCRIPTION, $this->response->getDescription());
        $this->assertEquals(CourseStub::COURSE_DIFFICULTY, $this->response->getDifficulty());
        $this->assertEquals(CourseStub::COURSE_DISPLAY_LEVEL, $this->response->getDisplayLevel());
        $this->assertEquals(
            new \DateInterval(CourseStub::COURSE_DURATION),
            $this->response->getDuration()
        );
        $this->assertEquals(CourseStub::COURSE_ID, $this->response->getId());
        $this->assertEquals(CourseStub::COURSE_IMAGE, $this->response->getImage());
        $this->assertEquals(CourseStub::COURSE_LICENSE, $this->response->getLicense());
        $this->assertEquals(CourseStub::COURSE_RATING, $this->response->getRating());
        $this->assertEquals(CourseStub::COURSE_SLUG, $this->response->getSlug());
        $this->assertEquals(CourseStub::COURSE_TITLE, $this->response->getTitle());
        $this->assertEquals(
            new \DateTime(CourseStub::COURSE_UPDATED_AT),
            $this->response->getUpdatedAt()
        );
    }
}
