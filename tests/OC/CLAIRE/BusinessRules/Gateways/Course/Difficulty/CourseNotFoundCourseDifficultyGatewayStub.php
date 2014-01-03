<?php

namespace OC\CLAIRE\BusinessRules\Gateways\Course\Difficulty;

use OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CourseNotFoundCourseDifficultyGatewayStub implements CourseDifficultyGateway
{
    /**
     * @return string
     */
    public function find($courseId)
    {
        throw new CourseNotFoundException();
    }

    public function update($courseId, $difficulty)
    {
        throw new CourseNotFoundException();
    }

}
