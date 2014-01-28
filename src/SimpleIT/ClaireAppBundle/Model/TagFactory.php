<?php
namespace SimpleIT\ClaireAppBundle\Model;
use SimpleIT\ClaireAppBundle\Model\CourseAssociation\Tag;

use SimpleIT\ClaireAppBundle\Model\CourseAssociation\Category;

/**
 * Class TagFactory
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class TagFactory
{
    /**
     * Create a tag
     *
     * @param array $tagResource
     *
     * @return Tag
     */
    public static function create(array $tagResource)
    {
        $tag = new Tag();
        if (isset($tagResource['id'])) {
            $tag->setId($tagResource['id']);
        }
        if (isset($tagResource['name'])) {
            $tag->setName($tagResource['name']);
        }
        if (isset($tagResource['slug'])) {
            $tag->setSlug($tagResource['slug']);
        }
        if (isset($tagResource['image'])) {
            $tag->setImage($tagResource['image']);
        }
        if (isset($tagResource['description'])) {
            $tag->setDescription($tagResource['description']);
        }
        if (isset($tagResource['category'])) {
            $category = CategoryFactory::create($tagResource['category']);
            $tag->setCategory($category);
        }
        if (isset($tagResource['totalTutorial'])) {
            $tag->setTotalTutorial($tagResource['totalTutorial']);
        }

        if (isset($tagResource['headlineCourse'])) {
            $headlineCourse = CourseFactory::create($tagResource['headlineCourse']);
            $tag->setHeadlineCourse($headlineCourse);
        }

        return $tag;
    }

    /**
     * Create a collection of tags
     *
     * @param array $tagResources The resources
     *
     * @return array The tags
     */
    public static function createCollection($tagResources)
    {
        $tags = array();
        foreach ($tagResources as $tagResource) {
            $tag = self::create($tagResource);
            $tags[] = $tag;
        }

        return $tags;
    }
}
