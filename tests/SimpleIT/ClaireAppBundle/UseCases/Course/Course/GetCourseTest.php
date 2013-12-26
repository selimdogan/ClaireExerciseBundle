<?php

namespace SimpleIT\ClaireAppBundle\UseCases\Course\Course;

use SimpleIT\ClaireAppBundle\Entities\Course\CourseStub;
use SimpleIT\ClaireAppBundle\Gateways\Course\Course\CourseNotFoundCourseGatewayStub;
use SimpleIT\ClaireAppBundle\Requestors\Course\Course\GetCourseRequest;
use SimpleIT\ClaireAppBundle\Responders\Course\Course\GetCourseResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */

/** @noinspection PhpUndefinedNamespaceInspection */
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
     * @expectedException SimpleIT\ClaireAppBundle\Gateways\Course\Course\CourseNotFoundException
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
            CourseStub::COURSE_DISPLAY_LEVEL,
            $this->response->getDisplayLevel()
        );
        $this->assertEquals(CourseStub::COURSE_ID, $this->response->getId());
        $this->assertEquals(CourseStub::COURSE_SLUG, $this->response->getSlug());
        $this->assertEquals(CourseStub::COURSE_TITLE, $this->response->getTitle());
        $this->assertEquals(
            new \DateTime(CourseStub::COURSE_UPDATED_AT),
            $this->response->getUpdatedAt()
        );
    }
}
