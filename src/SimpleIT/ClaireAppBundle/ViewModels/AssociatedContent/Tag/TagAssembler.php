<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\AssociatedContent\Tag;

use SimpleIT\ApiResourcesBundle\AssociatedContent\CategoryResource;
use SimpleIT\ApiResourcesBundle\AssociatedContent\TagResource;

class TagAssembler
{
    /**
     * @return Tag
     */
    public function writeTag(TagResource $tagResource, CategoryResource $categoryResource)
    {
        $tag = new Tag();
        $tag->title = $tagResource->getName();
        $tag->description = $tagResource->getDescription();
        if (null !== $tagResource->getImage()) {
            $tag->image = $tagResource->getImage();
        } else {
            $tag->image = $categoryResource->getImage();
        }

        return $tag;
    }
}
