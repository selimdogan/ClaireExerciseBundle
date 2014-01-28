<?php

namespace OC\CLAIRE\BusinessRules\Gateways\Course\Course;

use OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CourseNotFoundCourseContentGatewayStub implements CourseContentGateway
{
    /**
     * @return string
     */
    public function findPublished($courseIdentifier)
    {
        throw new CourseNotFoundException();
    }

    /**
     * @return string
     */
    public function findWaitingForPublication($courseId)
    {
        throw new CourseNotFoundException();
    }

    /**
     * @return string
     */
    public function findDraft($courseId)
    {
        throw new CourseNotFoundException();
    }

    /**
     * @return string
     */
    public function update($courseId, $content)
    {
        throw new CourseNotFoundException();
    }

}
