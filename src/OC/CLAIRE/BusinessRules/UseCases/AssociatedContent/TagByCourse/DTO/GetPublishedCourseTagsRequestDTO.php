<?php

namespace OC\CLAIRE\BusinessRules\UseCases\AssociatedContent\TagByCourse\DTO;

use OC\CLAIRE\BusinessRules\Requestors\AssociatedContent\TagByCourse\GetPublishedCourseTagsRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetPublishedCourseTagsRequestDTO implements GetPublishedCourseTagsRequest
{
    /**
     * @var int|string
     */
    public $courseIdentifier;

    public function __construct($courseIdentifier)
    {
        $this->courseIdentifier = $courseIdentifier;
    }

    /**
     * @return int|string
     */
    public function getCourseIdentifier()
    {
        return $this->courseIdentifier;
    }
}
