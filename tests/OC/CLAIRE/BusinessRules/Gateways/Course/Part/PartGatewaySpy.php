<?php

namespace OC\CLAIRE\BusinessRules\Gateways\Course\Part;

use OC\CLAIRE\BusinessRules\Entities\Course\Part\PartStub;
use SimpleIT\ApiResourcesBundle\Course\PartResource;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class PartGatewaySpy implements PartGateway
{
    /**
     * @var int
     */
    public $courseId;

    /**
     * @var int
     */
    public $partId;

    /**
     * @var PartResource
     */
    public $part;

    /**
     * @return PartResource
     */
    public function findDraft($courseId, $partId)
    {
        return new PartStub();
    }

    public function updateDraft($courseId, $partId, PartResource $part)
    {
        $this->courseId = $courseId;
        $this->partId = $partId;
        $this->part = $part;
    }
}
