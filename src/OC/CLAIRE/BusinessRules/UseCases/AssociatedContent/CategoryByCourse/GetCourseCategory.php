<?php

namespace OC\CLAIRE\BusinessRules\UseCases\AssociatedContent\CategoryByCourse;

use OC\CLAIRE\BusinessRules\Gateways\AssociatedContent\Category\CategoryByCourseGateway;
use OC\CLAIRE\BusinessRules\Requestors\UseCase;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
abstract class GetCourseCategory implements UseCase
{
    /**
     * @var CategoryByCourseGateway
     */
    protected $categoryByCourseGateway;

    public function setCategoryByCourseGateway(CategoryByCourseGateway $categoryByCourseGateway)
    {
        $this->categoryByCourseGateway = $categoryByCourseGateway;
    }
}
