<?php

namespace OC\CLAIRE\BusinessRules\UseCases\AssociatedContent\TagByCourse\DTO;

use OC\CLAIRE\BusinessRules\Responders\AssociatedContent\Tag\TagByCourse\GetCourseTagsResponse;
use OC\CLAIRE\BusinessRules\Responders\AssociatedContent\Tag\TagByCourse\TagResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetCourseTagsResponseDTO implements GetCourseTagsResponse
{
    /**
     * @var TagResponse
     */
    public $tags = array();

    /**
     * @return TagResponse[]
     */
    public function getTags()
    {
        return $this->tags;
    }

}
