<?php

namespace OC\CLAIRE\BusinessRules\Requestors\AssociatedContent\CategoryByCourse;

use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface GetDraftCourseCategoryRequest extends UseCaseRequest
{
    /**
     * @return int
     */
    public function getCourseId();
}
