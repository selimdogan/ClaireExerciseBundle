<?php

namespace OC\CLAIRE\BusinessRules\UseCases\AssociatedContent\CategoryByCourse\DTO;

use OC\CLAIRE\BusinessRules\Responders\AssociatedContent\Category\GetDraftCourseCategoryResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftCourseCategoryResponseDTO implements GetDraftCourseCategoryResponse
{
    /**
     * @var int
     */
    public $categoryId;

    /**
     * @var string
     */
    public $categoryName;

    /**
     * @var string
     */
    public $categorySlug;

    /**
     * @return int
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * @return string
     */
    public function getCategoryName()
    {
        return $this->categoryName;
    }

    /**
     * @return string
     */
    public function getCategorySlug()
    {
        return $this->categorySlug;
    }
}
