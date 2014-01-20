<?php

namespace OC\CLAIRE\BusinessRules\Gateways\Course\Toc;

use SimpleIT\ApiResourcesBundle\Course\PartResource;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class TocByCourseGatewayStub implements TocByCourseGateway
{
    /**
     * @return PartResource
     */
    public function findByStatus($courseId, $status)
    {
        return null;
    }

    /**
     * @return PartResource
     */
    public function update($courseId, PartResource $toc)
    {
        return null;
    }

    /**
     * @return PartResource
     */
    public function findDraft($courseId)
    {
        return null;
    }

    /**
     * @return PartResource
     */
    public function findWaitingForPublication($courseId)
    {
        return null;
    }

    /**
     * @return PartResource
     */
    public function findPublished($courseIdentifier)
    {
        return null;
    }
}
