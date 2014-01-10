<?php

namespace OC\CLAIRE\BusinessRules\Gateways\Course\Part;

use SimpleIT\ApiResourcesBundle\Course\PartResource;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface PartGateway
{
    /**
     * @return PartResource
     */
    public function findDraft($courseId, $partId);

    public function updateDraft($courseId, $partId, PartResource $part);
}
