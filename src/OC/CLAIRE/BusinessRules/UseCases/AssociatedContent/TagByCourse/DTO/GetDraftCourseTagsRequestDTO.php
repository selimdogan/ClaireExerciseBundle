<?php

namespace OC\CLAIRE\BusinessRules\UseCases\AssociatedContent\TagByCourse\DTO;

use OC\CLAIRE\BusinessRules\Requestors\AssociatedContent\TagByCourse\GetDraftCourseTagsRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftCourseTagsRequestDTO implements GetDraftCourseTagsRequest
{
    /**
     * @var int
     */
    public $courseId;

    public function __construct($courseId)
    {
        $this->courseId = $courseId;
    }

    /**
     * @return int
     */
    public function getCourseId()
    {
        return $this->courseId;
    }
}
