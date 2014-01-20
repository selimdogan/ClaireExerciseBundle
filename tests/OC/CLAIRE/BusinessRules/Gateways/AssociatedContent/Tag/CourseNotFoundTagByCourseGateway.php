<?php

namespace OC\CLAIRE\BusinessRules\Gateways\AssociatedContent\Tag;

use OC\CLAIRE\BusinessRules\Entities\AssociatedContent\Tag\Tag;
use OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CourseNotFoundTagByCourseGateway implements TagByCourseGateway
{
    /**
     * @return Tag[]
     */
    public function findDraft($courseId)
    {
        throw new CourseNotFoundException();
    }

    /**
     * @return Tag[]
     */
    public function findWaitingForPublication($courseId)
    {
        throw new CourseNotFoundException();
    }

    /**
     * @return Tag[]
     */
    public function findPublished($courseIdentifier)
    {
        throw new CourseNotFoundException();
    }

}
