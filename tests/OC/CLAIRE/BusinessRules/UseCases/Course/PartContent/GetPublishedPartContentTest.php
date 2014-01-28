<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\PartContent;

use OC\CLAIRE\BusinessRules\Gateways\Course\Part\PartContentGatewaySpy;
use OC\CLAIRE\BusinessRules\UseCases\Course\PartContent\DTO\GetPublishedPartContentRequestDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetPublishedPartContentTest extends GetPartContentTest
{
    /**
     * @test
     */
    public function GetPublishedContent()
    {
        $this->executeUseCase();
        $this->assertEquals(
            PartContentGatewaySpy::PUBLISHED_CONTENT,
            $this->response->getContent()
        );
    }

    protected function setUp()
    {
        $this->useCase = new GetPublishedPartContent();
        $this->request = new GetPublishedPartContentRequestDTO(self::COURSE_1_ID, self::PART_1_ID);
        parent::setUp();
    }
}
