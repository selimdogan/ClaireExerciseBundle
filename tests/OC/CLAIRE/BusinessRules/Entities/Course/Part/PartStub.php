<?php

namespace OC\CLAIRE\BusinessRules\Entities\Course\Part;

use OC\CLAIRE\BusinessRules\Entities\Course\Course\Difficulty;
use SimpleIT\ApiResourcesBundle\Course\PartResource;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class PartStub extends PartResource
{
    const DESCRIPTION = 'Part 1 description';

    const DIFFICULTY = Difficulty::EASY;

    const ID = 10;

    protected $description = self::DESCRIPTION;

    protected $difficulty = self::DIFFICULTY;

    protected $id = self::ID;

}
