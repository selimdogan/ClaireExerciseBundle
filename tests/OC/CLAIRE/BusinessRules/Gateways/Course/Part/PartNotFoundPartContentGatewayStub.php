<?php

namespace OC\CLAIRE\BusinessRules\Gateways\Course\Part;

use OC\CLAIRE\BusinessRules\Exceptions\Course\Part\PartNotFoundException;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class PartNotFoundPartContentGatewayStub implements PartContentGateway
{
    /**
     * @return string
     */
    public function findPublished($courseIdentifier, $partIdentifier)
    {
        throw new PartNotFoundException();
    }

    /**
     * @return string
     */
    public function findWaitingForPublication($courseId, $partId)
    {
        throw new PartNotFoundException();
    }

    /**
     * @return string
     */
    public function findDraft($courseId, $partId)
    {
        throw new PartNotFoundException();
    }

    /**
     * @return string
     */
    public function update($courseId, $partId, $content)
    {
        throw new PartNotFoundException();
    }

}
