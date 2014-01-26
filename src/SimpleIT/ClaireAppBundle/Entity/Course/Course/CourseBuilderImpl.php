<?php

namespace SimpleIT\ClaireAppBundle\Entity\Course\Course;

use OC\CLAIRE\BusinessRules\Entities\Course\Course\CourseBuilder;
use SimpleIT\ApiResourcesBundle\Course\CourseResource;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CourseBuilderImpl extends CourseBuilder
{
    private function __construct()
    {
        $this->course = new CourseResource();
    }

    public static function create()
    {
        return new CourseBuilderImpl();
    }
}
