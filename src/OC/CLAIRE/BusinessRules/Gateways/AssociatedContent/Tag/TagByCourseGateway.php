<?php

namespace OC\CLAIRE\BusinessRules\Gateways\AssociatedContent\Tag;

use OC\CLAIRE\BusinessRules\Entities\AssociatedContent\Tag\Tag;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface TagByCourseGateway
{
    /**
     * @return Tag[]
     */
    public function findDraft($courseId);

    /**
     * @return Tag[]
     */
    public function findWaitingForPublication($courseId);

    /**
     * @return Tag[]
     */
    public function findPublished($courseIdentifier);
}
