<?php

namespace OC\CLAIRE\BusinessRules\Responders\AssociatedContent\Tag\TagByCourse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface TagResponse
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getImage();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getSlug();
}
