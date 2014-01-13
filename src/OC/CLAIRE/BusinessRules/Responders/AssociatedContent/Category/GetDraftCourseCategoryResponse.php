<?php

namespace OC\CLAIRE\BusinessRules\Responders\AssociatedContent\Category;

use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface GetDraftCourseCategoryResponse extends UseCaseResponse
{
    /**
     * @return int
     */
    public function getCategoryId();

    /**
     * @return string
     */
    public function getCategoryName();
}
