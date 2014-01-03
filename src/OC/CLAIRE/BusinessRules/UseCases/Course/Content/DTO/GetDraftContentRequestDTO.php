<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Content\DTO;

use OC\CLAIRE\BusinessRules\Requestors\Course\Content\GetDraftContentRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftContentRequestDTO implements GetDraftContentRequest
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
