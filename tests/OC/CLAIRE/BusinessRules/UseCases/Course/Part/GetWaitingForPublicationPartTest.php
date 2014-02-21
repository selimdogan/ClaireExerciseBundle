<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Part;

use OC\CLAIRE\BusinessRules\UseCases\Course\Part\DTO\GetWaitingForPublicationPartRequestDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetWaitingForPublicationPartTest extends GetPartTest
{
    protected function setUp()
    {
        $this->request = new GetWaitingForPublicationPartRequestDTO(self::COURSE_1_ID, self::PART_1_ID);
        $this->useCase = new GetWaitingForPublicationPart();
        parent::setUp();
    }

}
