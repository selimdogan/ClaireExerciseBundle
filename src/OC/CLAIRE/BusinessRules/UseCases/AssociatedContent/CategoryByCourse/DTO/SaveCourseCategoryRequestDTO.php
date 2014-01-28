<?php

namespace OC\CLAIRE\BusinessRules\UseCases\AssociatedContent\CategoryByCourse\DTO;

use OC\CLAIRE\BusinessRules\Requestors\AssociatedContent\CategoryByCourse\SaveCourseCategoryRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class SaveCourseCategoryRequestDTO implements SaveCourseCategoryRequest
{
    /**
     * @var int
     */
    public $courseId;

    /**
     * @var int
     */
    public $categoryId;

    public function __construct($courseId, $categoryId)
    {
        $this->categoryId = $categoryId;
        $this->courseId = $courseId;
    }

    /**
     * @return int
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * @return int
     */
    public function getCourseId()
    {
        return $this->courseId;
    }
}
