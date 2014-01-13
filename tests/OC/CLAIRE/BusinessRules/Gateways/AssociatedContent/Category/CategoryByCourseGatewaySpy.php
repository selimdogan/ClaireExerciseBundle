<?php

namespace OC\CLAIRE\BusinessRules\Gateways\AssociatedContent\Category;

use OC\CLAIRE\BusinessRules\Entities\AssociatedContent\Category\Category;
use OC\CLAIRE\BusinessRules\Entities\AssociatedContent\CategoryStub;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CategoryByCourseGatewaySpy implements CategoryByCourseGateway
{
    /**
     * @var int
     */
    public $categoryId;

    /**
     * @var int
     */
    public $courseId;

    /**
     * @return Category
     */
    public function findDraft($courseId)
    {
        return new CategoryStub();
    }

    public function update($categoryId, $courseId)
    {
        $this->categoryId = $categoryId;
        $this->courseId = $courseId;
    }

}
