<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\CourseContent;

use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseContentGatewaySpy;
use
    OC\CLAIRE\BusinessRules\UseCases\Course\CourseContent\DTO\GetWaitingForPublicationCourseContentRequestDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetWaitingForPublicationTest extends GetCourseContentTest
{
    /**
     * @test
     */
    public function GetDraftContent()
    {
        $this->executeUseCase();
        $this->assertEquals(
            CourseContentGatewaySpy::WAITING_FOR_PUBLICATION_CONTENT,
            $this->response->getContent()
        );
    }

    protected function setup()
    {
        $this->useCase = new GetWaitingForPublicationCourseContent();
        $this->request = new GetWaitingForPublicationCourseContentRequestDTO(self::COURSE_ID_1);
        parent::setUp();
    }
}
