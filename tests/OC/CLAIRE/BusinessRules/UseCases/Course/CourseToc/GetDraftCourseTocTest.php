<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\CourseToc;

use OC\CLAIRE\BusinessRules\Entities\Course\Course\Status;
use OC\CLAIRE\BusinessRules\UseCases\Course\CourseToc\DTO\GetDraftCourseTocRequestDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftCourseTocTest extends GetCourseTocTest
{
    /**
     * @test
     */
    public function ReturnCourseToc()
    {
        $this->executeUseCase();
        $this->assertCourseToc();
        $this->assertEquals(Status::DRAFT, $this->response->getCourseToc()->getStatus());
    }

    protected function setUp()
    {
        $this->useCase = new GetDraftCourseToc();
        $this->request = new GetDraftCourseTocRequestDTO(self::COURSE_ID);
        parent::setUp();
    }
}
