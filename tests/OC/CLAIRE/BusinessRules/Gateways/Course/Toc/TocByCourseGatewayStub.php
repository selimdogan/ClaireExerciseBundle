<?php

namespace OC\CLAIRE\BusinessRules\Gateways\Course\Toc;

use SimpleIT\ApiResourcesBundle\Course\PartResource;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\DraftTocStub1;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\PublishedTocStub1;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\WaitingForPublicationTocStub1;

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
        return new DraftTocStub1();
    }

    /**
     * @return PartResource
     */
    public function findWaitingForPublication($courseId)
    {
        return new WaitingForPublicationTocStub1();
    }

    /**
     * @return PartResource
     */
    public function findPublished($courseIdentifier)
    {
        return new PublishedTocStub1();
    }
}
