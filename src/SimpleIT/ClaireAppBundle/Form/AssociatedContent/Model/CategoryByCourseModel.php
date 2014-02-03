<?php

namespace SimpleIT\ClaireAppBundle\Form\AssociatedContent\Model;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CategoryByCourseModel
{
    /**
     * @var int
     */
    private $categoryId;

    public function __construct($categoryId = null)
    {
        $this->categoryId = $categoryId;
    }

    /**
     * @return int
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;
    }
}
