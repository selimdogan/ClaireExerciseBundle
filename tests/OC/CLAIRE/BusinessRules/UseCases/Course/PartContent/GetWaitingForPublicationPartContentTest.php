<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\PartContent;

use OC\CLAIRE\BusinessRules\Gateways\Course\Part\PartContentGatewaySpy;
use
    OC\CLAIRE\BusinessRules\UseCases\Course\PartContent\DTO\GetWaitingForPublicationPartContentRequestDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetWaitingForPublicationPartContentTest extends GetPartContentTest
{
    /**
     * @test
     */
    public function GetPublishedContent()
    {
        $this->executeUseCase();
        $this->assertEquals(
            PartContentGatewaySpy::WAITING_FOR_PUBLICATION_CONTENT,
            $this->response->getContent()
        );
    }

    protected function setUp()
    {
        $this->useCase = new GetWaitingForPublicationPartContent();
        $this->request = new GetWaitingForPublicationPartContentRequestDTO(self::COURSE_1_ID, self::PART_1_ID);
        parent::setUp();
    }

}
