<?php

namespace SimpleIT\ClaireAppBundle\Entity\Course\Part;

use OC\CLAIRE\BusinessRules\Entities\Course\Part\PartBuilder;
use SimpleIT\ApiResourcesBundle\Course\PartResource;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class PartBuilderImpl extends PartBuilder
{
    public function __construct()
    {
        $this->part = new PartResource();
    }

    public static function create()
    {
        return new PartBuilderImpl();
    }
}
