<?php

namespace OC\CLAIRE\BusinessRules\UseCases\AssociatedContent\CategoryByCourse\DTO;

use OC\CLAIRE\BusinessRules\Requestors\AssociatedContent\CategoryByCourse\GetDraftCourseCategoryRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftCourseCategoryRequestDTO implements GetDraftCourseCategoryRequest
{
    /**
     * @var int
     */
    public $courseId;

    public function __construct($courseId)
    {
        $this->courseId = $courseId;
    }

    /**
     * @return int
     */
    public function getCourseId()
    {
        return $this->courseId;
    }

}
