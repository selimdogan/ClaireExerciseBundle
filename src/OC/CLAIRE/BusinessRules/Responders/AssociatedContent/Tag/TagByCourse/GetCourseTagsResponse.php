<?php

namespace OC\CLAIRE\BusinessRules\Responders\AssociatedContent\Tag\TagByCourse;

use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface GetCourseTagsResponse extends UseCaseResponse
{
    /**
     * @return TagResponse[]
     */
    public function getTags();
}
