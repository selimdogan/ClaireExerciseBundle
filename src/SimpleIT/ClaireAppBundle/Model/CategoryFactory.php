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
        if (isset($categoryRessource['name'])) {
            $category->setName($categoryRessource['name']);
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
        if (isset($categoryRessource['state'])) {
            $category->setState($categoryRessource['state']);
        }
        if (isset($categoryRessource['position'])) {
            $category->setPosition($categoryRessource['position']);
        }

        return $category;
    }

    /**
     * Create a collection of categories
     *
     * @param array $categoryResources The resources
     *
     * @return array The categories
     */
    public static function createCollection($categoryResources)
    {
        $categories = array();
        foreach ($categoryResources as $categoryResource) {
            $category = self::create($categoryResource);
            $categories[] = $category;
        }

        return $categories;
    }
}
