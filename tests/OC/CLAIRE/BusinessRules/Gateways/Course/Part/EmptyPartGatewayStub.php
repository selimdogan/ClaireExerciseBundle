<?php

namespace OC\CLAIRE\BusinessRules\Gateways\Course\Part;

use OC\CLAIRE\BusinessRules\Entities\Course\Part\EmptyPartStub;
use SimpleIT\ApiResourcesBundle\Course\PartResource;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class EmptyPartGatewayStub implements PartGateway
{
    /**
     * @return PartResource
     */
    public function findDraft($courseId, $partId)
    {
        return new EmptyPartStub();
    }

    /**
     * @return PartResource
     */
    public function findWaitingForPublication($courseId, $partId)
    {
        return new EmptyPartStub();
    }

    /**
     * @return PartResource
     */
    public function findPublished($courseIdentifier, $partIdentifier)
    {
        return new EmptyPartStub();
    }

    public function updateDraft($courseId, $partId, PartResource $part)
    {
        return null;
    }
}
