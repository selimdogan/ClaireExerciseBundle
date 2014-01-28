<?php

namespace OC\CLAIRE\BusinessRules\Gateways\Course\Part;

use OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CourseNotFoundPartContentGateway implements PartContentGateway
{
    /**
     * @return string
     */
    public function findPublished($courseIdentifier, $partIdentifier)
    {
        throw new CourseNotFoundException();
    }

    /**
     * @return string
     */
    public function findWaitingForPublication($courseId, $partId)
    {
        throw new CourseNotFoundException();
    }

    /**
     * @return string
     */
    public function findDraft($courseId, $partId)
    {
        throw new CourseNotFoundException();
    }

    /**
     * @return string
     */
    public function update($courseId, $partId, $content)
    {
        throw new CourseNotFoundException();
    }

}
