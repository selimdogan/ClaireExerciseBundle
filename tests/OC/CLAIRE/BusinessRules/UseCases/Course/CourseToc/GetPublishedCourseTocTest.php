<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\CourseToc;

use OC\CLAIRE\BusinessRules\Entities\Course\Course\Status;
use OC\CLAIRE\BusinessRules\UseCases\Course\CourseToc\DTO\GetPublishedCourseTocRequestDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetPublishedCourseTocTest extends GetCourseTocTest
{
    /**
     * @test
     */
    public function ReturnCourseToc()
    {
        $this->executeUseCase();
        $this->assertCourseToc();
        $this->assertEquals(Status::PUBLISHED, $this->response->getCourseToc()->getStatus());
    }

    protected function setUp()
    {
        $this->useCase = new GetPublishedCourseToc();
        $this->request = new GetPublishedCourseTocRequestDTO(self::COURSE_ID);
        parent::setUp();
    }

}
