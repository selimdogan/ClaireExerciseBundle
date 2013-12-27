<?php

namespace SimpleIT\ClaireAppBundle\Entities\Course\Part;

use SimpleIT\ApiResourcesBundle\Course\PartResource;

/**
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
interface PartFactory
{
    /**
     * @return PartResource
     */
    public function make($subtype);
}
