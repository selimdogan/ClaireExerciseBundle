<?php

namespace OC\CLAIRE\BusinessRules\Gateways\AssociatedContent\Category;

use OC\CLAIRE\BusinessRules\Entities\AssociatedContent\Category\Category;
use OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CourseNotFoundCategoryByCourseGatewayStub implements CategoryByCourseGateway
{
    /**
     * @return Category
     */
    public function findDraft($courseId)
    {
        throw new CourseNotFoundException();
    }

    public function update($categoryId, $courseId)
    {
        throw new CourseNotFoundException();
    }

}
