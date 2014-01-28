<?php

namespace OC\CLAIRE\BusinessRules\Requestors\Course\CourseToc;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface GetWaitingForPublicationCourseTocRequest extends GetCourseTocRequest
{
    /**
     * @return int
     */
    public function getCourseId();

}
