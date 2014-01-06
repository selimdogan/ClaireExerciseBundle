<?php

namespace OC\CLAIRE\BusinessRules\Entities\Course\Part;

use SimpleIT\ApiResourcesBundle\Course\PartResource;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class EmptyPartStub extends PartResource
{
    public function getDifficulty()
    {
        return null;
    }
}
