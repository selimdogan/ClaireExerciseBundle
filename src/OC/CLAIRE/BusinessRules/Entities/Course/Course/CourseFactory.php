<?php

namespace OC\CLAIRE\BusinessRules\Entities\Course\Course;

use SimpleIT\ApiResourcesBundle\Course\CourseResource;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface CourseFactory
{
    /**
     * @return CourseResource
     */
    public function make();
}
