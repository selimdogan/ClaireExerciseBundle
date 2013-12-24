<?php

namespace SimpleIT\ClaireAppBundle\UseCases\Course\Course;

use SimpleIT\ClaireAppBundle\Entities\Course\PublishedCourseStub;
use SimpleIT\ClaireAppBundle\Gateways\Course\Course\CourseGatewayStub;
use SimpleIT\ClaireAppBundle\Gateways\Course\Course\CourseNotFoundCourseGatewayStub;
use SimpleIT\ClaireAppBundle\Responders\Course\Course\GetCourseResponse;
use SimpleIT\ClaireAppBundle\UseCases\Course\Course\DTO\GetPublishedCourseRequestDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetPublishedCourseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var GetPublishedCourse
     */
    private $useCase;

    /**
     * @test
     * @expectedException SimpleIT\ClaireAppBundle\Gateways\Course\Course\CourseNotFoundException
     */
    public function NonExistingCourse_ThrowException()
    {
        $this->useCase->setCourseGateway(new CourseNotFoundCourseGatewayStub());
        $request = new GetPublishedCourseRequestDTO(1);
        $this->useCase->execute($request);
    }

    /**
     * @test
     */
    public function ReturnCourse()
    {
        $this->useCase->setCourseGateway(new CourseGatewayStub());
        $request = new GetPublishedCourseRequestDTO(1);
        /** @var GetCourseResponse $response */
        $response = $this->useCase->execute($request);

        $this->assertEquals(
            new \DateTime(PublishedCourseStub::COURSE_CREATED_AT),
            $response->getCreatedAt()
        );
        $this->assertEquals(
            PublishedCourseStub::COURSE_DISPLAY_LEVEL,
            $response->getDisplayLevel()
        );
        $this->assertEquals(PublishedCourseStub::COURSE_ID, $response->getId());
        $this->assertEquals(PublishedCourseStub::COURSE_SLUG, $response->getSlug());
        $this->assertEquals(PublishedCourseStub::COURSE_STATUS, $response->getStatus());
        $this->assertEquals(PublishedCourseStub::COURSE_TITLE, $response->getTitle());
        $this->assertEquals(new \DateTime(PublishedCourseStub::COURSE_UPDATED_AT), $response->getUpdatedAt());
    }

    protected function setup()
    {
        $this->useCase = new GetPublishedCourse();
    }
}
