<?php

namespace OC\CLAIRE\BusinessRules\Gateways\AssociatedContent\Tag;

use OC\CLAIRE\BusinessRules\Entities\AssociatedContent\Tag\Tag;
use OC\CLAIRE\BusinessRules\Entities\AssociatedContent\Tag\TagStub1;
use OC\CLAIRE\BusinessRules\Entities\AssociatedContent\Tag\TagStub2;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class TagByCourseGatewaySpy implements TagByCourseGateway
{
    /**
     * @return Tag[]
     */
    public function findDraft($courseId)
    {
        return array(new TagStub1(), new TagStub2());
    }

    /**
     * @return Tag[]
     */
    public function findWaitingForPublication($courseId)
    {
        return array(new TagStub1(), new TagStub2());
    }

    /**
     * @return Tag[]
     */
    public function findPublished($courseIdentifier)
    {
        return array(new TagStub1(), new TagStub2());
    }

}
