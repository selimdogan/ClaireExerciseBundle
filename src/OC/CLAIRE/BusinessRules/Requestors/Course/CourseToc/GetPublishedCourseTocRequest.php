<?php

namespace OC\CLAIRE\BusinessRules\Requestors\Course\CourseToc;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface GetPublishedCourseTocRequest extends GetCourseTocRequest
{
    /**
     * @return int|string
     */
    public function getCourseIdentifier();
}
