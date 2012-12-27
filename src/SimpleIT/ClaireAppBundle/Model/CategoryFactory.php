<?php
namespace SimpleIT\ClaireAppBundle\Model;

use SimpleIT\ClaireAppBundle\Model\CourseAssociation\Category;

/**
 * Class CategoryFactory
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class CategoryFactory
{
    /**
     * Create a category
     *
     * @param array $categoryRessource
     *
     * @return Category
     */
    public static function create(array $categoryRessource)
    {
        $category = new Category();
        if (isset($categoryRessource['id'])) {
            $category->setId($categoryRessource['id']);
        }
        if (isset($categoryRessource['title'])) {
            $category->setTitle($categoryRessource['title']);
        }
        if (isset($categoryRessource['slug'])) {
            $category->setSlug($categoryRessource['slug']);
        }
        if (isset($categoryRessource['image'])) {
            $category->setImage($categoryRessource['image']);
        }
        if (isset($categoryRessource['description'])) {
            $category->setDescription($categoryRessource['description']);
        }
        if (isset($categoryRessource['position'])) {
            $category->setPosition($categoryRessource['position']);
        }

        return $category;
    }
}
