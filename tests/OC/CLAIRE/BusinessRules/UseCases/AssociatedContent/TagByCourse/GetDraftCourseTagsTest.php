<?php

namespace OC\CLAIRE\BusinessRules\UseCases\AssociatedContent\TagByCourse;

use OC\CLAIRE\BusinessRules\Gateways\AssociatedContent\Tag\TagByCourseGatewaySpy;
use
    OC\CLAIRE\BusinessRules\UseCases\AssociatedContent\TagByCourse\DTO\GetDraftCourseTagsRequestDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftCourseTagsTest extends GetCourseTagsTest
{
    /**
     * @test
     */
    public function ReturnTags()
    {
        $this->executeUseCase();
        $this->assertResponse();
    }

    protected function setup()
    {
        $this->useCase = new GetDraftCourseTags();
        $this->useCase->setTagByCourseGateway(new TagByCourseGatewaySpy());
        $this->request = new GetDraftCourseTagsRequestDTO(self::COURSE_ID);
    }
}
