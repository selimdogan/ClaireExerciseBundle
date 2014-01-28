<?php

namespace OC\CLAIRE\BusinessRules\Requestors\Course\CourseToc;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface GetDraftCourseTocRequest extends GetCourseTocRequest
{
    /**
     * @return int
     */
    public function getCourseId();
}
