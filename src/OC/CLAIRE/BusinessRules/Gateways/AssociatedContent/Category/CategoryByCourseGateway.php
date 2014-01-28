<?php

namespace OC\CLAIRE\BusinessRules\Gateways\AssociatedContent\Category;

use OC\CLAIRE\BusinessRules\Entities\AssociatedContent\Category\Category;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface CategoryByCourseGateway
{
    /**
     * @return Category
     */
    public function findDraft($courseId);

    public function update($categoryId, $courseId);
}
