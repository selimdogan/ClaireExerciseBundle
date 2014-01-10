<?php

namespace OC\CLAIRE\BusinessRules\Entities\Course\Part;

use OC\CLAIRE\BusinessRules\Entities\Course\Course\Difficulty;
use SimpleIT\ApiResourcesBundle\Course\PartResource;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class PartStub extends PartResource
{
    const DIFFICULTY = Difficulty::EASY;

    public function getDifficulty()
    {
        return self::DIFFICULTY;
    }

}
