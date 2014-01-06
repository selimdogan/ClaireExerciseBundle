<?php

namespace OC\CLAIRE\BusinessRules\Gateways\Course\Part;

use OC\CLAIRE\BusinessRules\Exceptions\Course\Part\PartNotFoundException;
use SimpleIT\ApiResourcesBundle\Course\PartResource;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class PartNotFoundGatewayStub implements PartGateway
{
    /**
     * @return PartResource
     */
    public function findDraft($courseId, $partId)
    {
        throw new PartNotFoundException();
    }

    public function updateDraft($courseId, $partId, PartResource $part)
    {
        throw new PartNotFoundException();
    }

}
