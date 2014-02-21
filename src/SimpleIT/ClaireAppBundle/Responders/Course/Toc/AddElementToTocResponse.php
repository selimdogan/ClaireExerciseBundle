<?php

namespace SimpleIT\ClaireAppBundle\Responders\Course\Toc;

use SimpleIT\ApiResourcesBundle\Course\PartResource;

/**
 * Interface AddElementToTocResponse
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
interface AddElementToTocResponse
{
    /**
     * @return PartResource
     */
    public function getToc();

    /**
     * @return PartResource
     */
    public function getNewElement();
}
