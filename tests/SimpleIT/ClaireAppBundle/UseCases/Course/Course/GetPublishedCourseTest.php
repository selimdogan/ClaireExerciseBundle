<?php

namespace SimpleIT\ClaireAppBundle\UseCases\Course\Course;

use SimpleIT\ClaireAppBundle\Entities\Course\PublishedCourseStub;
use SimpleIT\ClaireAppBundle\Gateways\Course\Course\CourseGatewayStub;
use SimpleIT\ClaireAppBundle\UseCases\Course\Course\DTO\GetPublishedCourseRequestDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetPublishedCourseTest extends GetCourseTest
{

    /**
     * @test
     */
    public function CourseId_ReturnCourse()
    {
        $this->executeUseCase();
        $this->assertCourse();
        $this->assertEquals(PublishedCourseStub::COURSE_STATUS, $this->response->getStatus());
    }

    /**
     * @test
     */
    public function CourseSlug_ReturnCourse()
    {
        $this->request = new GetPublishedCourseRequestDTO(self::COURSE_SLUG);
        $this->executeUseCase();
        $this->assertCourse();
        $this->assertEquals(PublishedCourseStub::COURSE_STATUS, $this->response->getStatus());
    }

    protected function setup()
    {
        $this->useCase = new GetPublishedCourse();
        $this->request = new GetPublishedCourseRequestDTO(self::COURSE_ID);
        $this->useCase->setCourseGateway(new CourseGatewayStub());
    }
}
