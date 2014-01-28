<?php

namespace OC\CLAIRE\BusinessRules\UseCases\AssociatedContent\TagByCourse\DTO;

use OC\CLAIRE\BusinessRules\Responders\AssociatedContent\Tag\TagByCourse\TagResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class TagResponseDTO implements TagResponse
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $slug;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $image;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }
}
