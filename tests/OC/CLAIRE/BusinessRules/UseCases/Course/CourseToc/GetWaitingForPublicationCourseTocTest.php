<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\CourseToc;

use OC\CLAIRE\BusinessRules\Entities\Course\Course\Status;
use
    OC\CLAIRE\BusinessRules\UseCases\Course\CourseToc\DTO\GetWaitingForPublicationCourseTocRequestDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetWaitingForPublicationCourseTocTest extends GetCourseTocTest
{
    /**
     * @test
     */
    public function ReturnCourseToc()
    {
        $this->executeUseCase();
        $this->assertCourseToc();
        $this->assertEquals(Status::WAITING_FOR_PUBLICATION, $this->response->getCourseToc()->getStatus());
    }

    protected function setUp()
    {
        $this->useCase = new GetWaitingForPublicationCourseToc();
        $this->request = new GetWaitingForPublicationCourseTocRequestDTO(self::COURSE_ID);
        parent::setUp();
    }
}
