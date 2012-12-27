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
        return $tag;
    }

    /**
     * Create a collection of tags
     *
     * @param array $tagResources The resources
     *
     * @return array The tags
     */
    public static function createCollection(array $tagResources)
    {
        $tags = array();
        foreach ($tagResources as $tagResource) {
            $tag = self::create($tagResource);
            $tags[] = $tag;
        }
        return $tags;
    }
}
