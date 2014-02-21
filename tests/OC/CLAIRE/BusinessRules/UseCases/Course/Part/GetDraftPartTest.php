<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Part;

use OC\CLAIRE\BusinessRules\UseCases\Course\Part\DTO\GetDraftPartRequestDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftPartTest extends GetPartTest
{
    protected function setUp()
    {
        $this->request = new GetDraftPartRequestDTO(self::COURSE_1_ID, self::PART_1_ID);
        $this->useCase = new GetDraftPart();
        parent::setUp();
    }

}
