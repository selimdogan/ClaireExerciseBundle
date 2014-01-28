<?php

namespace OC\CLAIRE\BusinessRules\Entities\Course\Course;

use SimpleIT\ApiResourcesBundle\Course\CourseResource;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class EmptyCourseStub extends CourseResource
{
    public function getDifficulty()
    {
        return null;
    }

}
