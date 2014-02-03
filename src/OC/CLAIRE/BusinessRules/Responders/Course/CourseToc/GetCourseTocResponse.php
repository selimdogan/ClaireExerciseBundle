<?php

namespace OC\CLAIRE\BusinessRules\Responders\Course\CourseToc;

use SimpleIT\ApiResourcesBundle\Course\CourseResource;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface GetCourseTocResponse
{
    /**
     * @return CourseResource
     */
    public function getCourseToc();
}
