<?php

namespace OC\CLAIRE\BusinessRules\UseCases\AssociatedContent\TagByCourse;

use OC\CLAIRE\BusinessRules\Gateways\AssociatedContent\Tag\TagByCourseGatewaySpy;
use
    OC\CLAIRE\BusinessRules\UseCases\AssociatedContent\TagByCourse\DTO\GetPublishedCourseTagsRequestDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetPublishedCourseTagsTest extends GetCourseTagsTest
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
        $this->useCase = new GetPublishedCourseTags();
        $this->useCase->setTagByCourseGateway(new TagByCourseGatewaySpy());
        $this->request = new GetPublishedCourseTagsRequestDTO(self::COURSE_ID);
    }
}
