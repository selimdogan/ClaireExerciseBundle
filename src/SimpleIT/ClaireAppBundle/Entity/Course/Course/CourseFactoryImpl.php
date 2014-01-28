<?php

namespace SimpleIT\ClaireAppBundle\Entity\Course\Course;

use OC\CLAIRE\BusinessRules\Entities\Course\Course\CourseFactory;
use SimpleIT\ApiResourcesBundle\Course\CourseResource;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CourseFactoryImpl implements CourseFactory
{
    /**
     * @return CourseResource
     */
    public function make()
    {
        return new CourseResource();
    }
}
