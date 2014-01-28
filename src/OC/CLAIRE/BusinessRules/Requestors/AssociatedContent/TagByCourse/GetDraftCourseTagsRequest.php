<?php

namespace OC\CLAIRE\BusinessRules\Requestors\AssociatedContent\TagByCourse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface GetDraftCourseTagsRequest extends GetCourseTagsRequest
{
    /**
     * @return int
     */
    public function getCourseId();
}
