<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\CourseContent;

use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseContentGatewaySpy;
use OC\CLAIRE\BusinessRules\UseCases\Course\CourseContent\DTO\GetPublishedCourseContentRequestDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetPublishedContentTest extends GetCourseContentTest
{
    /**
     * @test
     */
    public function GetPublishedContent()
    {
        $this->executeUseCase();
        $this->assertEquals(CourseContentGatewaySpy::PUBLISHED_CONTENT, $this->response->getContent());
    }

    protected function setup()
    {
        $this->useCase = new GetPublishedCourseContent();
        $this->request = new GetPublishedCourseContentRequestDTO(self::COURSE_ID_1);
        parent::setUp();
    }
}
