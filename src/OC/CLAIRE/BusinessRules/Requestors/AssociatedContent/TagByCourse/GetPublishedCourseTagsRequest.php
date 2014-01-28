<?php

namespace OC\CLAIRE\BusinessRules\Requestors\AssociatedContent\TagByCourse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface GetPublishedCourseTagsRequest extends GetCourseTagsRequest
{
    /**
     * @return int|string
     */
    public function getCourseIdentifier();
}
