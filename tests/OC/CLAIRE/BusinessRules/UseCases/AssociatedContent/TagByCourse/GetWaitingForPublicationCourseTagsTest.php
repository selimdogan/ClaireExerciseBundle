<?php

namespace OC\CLAIRE\BusinessRules\UseCases\AssociatedContent\TagByCourse;

use OC\CLAIRE\BusinessRules\Gateways\AssociatedContent\Tag\TagByCourseGatewaySpy;
use
    OC\CLAIRE\BusinessRules\UseCases\AssociatedContent\TagByCourse\DTO\GetWaitingForPublicationCourseTagsRequestDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetWaitingForPublicationCourseTagsTest extends GetCourseTagsTest
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
        $this->useCase = new GetWaitingForPublicationCourseTags();
        $this->useCase->setTagByCourseGateway(new TagByCourseGatewaySpy());
        $this->request = new GetWaitingForPublicationCourseTagsRequestDTO(self::COURSE_ID);
    }
}
