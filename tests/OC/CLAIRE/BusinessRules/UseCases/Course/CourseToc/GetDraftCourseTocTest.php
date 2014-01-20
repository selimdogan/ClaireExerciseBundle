<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\CourseToc;

use OC\CLAIRE\BusinessRules\Gateways\Course\Toc\TocByCourseGatewayStub;
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
//        $this->assertCourseToc();
    }

    protected function setUp()
    {
        $this->useCase = new GetDraftCourseToc();
        $this->request = new GetDraftCourseTocRequestDTO(self::COURSE_ID);
        $this->useCase->setTocByCourseGateway(new TocByCourseGatewayStub());
    }
}
