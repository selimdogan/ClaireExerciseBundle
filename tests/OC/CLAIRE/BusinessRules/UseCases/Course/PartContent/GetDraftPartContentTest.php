<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\PartContent;

use OC\CLAIRE\BusinessRules\Gateways\Course\Part\PartContentGatewaySpy;
use OC\CLAIRE\BusinessRules\UseCases\Course\PartContent\DTO\GetDraftPartContentRequestDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftPartContentTest extends GetPartContentTest
{
    /**
     * @test
     */
    public function GetDraftContent()
    {
        $this->executeUseCase();
        $this->assertEquals(PartContentGatewaySpy::DRAFT_CONTENT, $this->response->getContent());
    }

    protected function setUp()
    {
        $this->useCase = new GetDraftPartContent();
        $this->request = new GetDraftPartContentRequestDTO(self::COURSE_1_ID, self::PART_1_ID);
        parent::setUp();
    }
}
