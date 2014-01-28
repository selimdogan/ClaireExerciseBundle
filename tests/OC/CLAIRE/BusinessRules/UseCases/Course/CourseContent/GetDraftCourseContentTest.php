<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\CourseContent;

use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseContentGatewaySpy;
use OC\CLAIRE\BusinessRules\UseCases\Course\CourseContent\DTO\GetDraftCourseContentRequestDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftCourseContentTest extends GetCourseContentTest
{

    /**
     * @test
     */
    public function GetDraftContent()
    {
        $this->executeUseCase();
        $this->assertEquals(CourseContentGatewaySpy::DRAFT_CONTENT, $this->response->getContent());
    }

    protected function setup()
    {
        $this->useCase = new GetDraftCourseContent();
        $this->request = new GetDraftCourseContentRequestDTO(self::COURSE_ID_1);
        parent::setUp();
    }
}
