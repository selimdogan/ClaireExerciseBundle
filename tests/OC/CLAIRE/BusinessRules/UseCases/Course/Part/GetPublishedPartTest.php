<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Part;

use OC\CLAIRE\BusinessRules\UseCases\Course\Part\DTO\GetPublishedPartRequestDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetPublishedPartTest extends GetPartTest
{
    protected function setUp()
    {
        $this->request = new GetPublishedPartRequestDTO(self::COURSE_1_ID, self::PART_1_ID);
        $this->useCase = new GetPublishedPart();
        parent::setUp();
    }
}
