<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\PartContent\DTO;

use OC\CLAIRE\BusinessRules\Requestors\Course\PartContent\GetDraftPartContentRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftPartContentRequestDTO implements GetDraftPartContentRequest
{
    /**
     * @var int
     */
    public $courseId;

    /**
     * @var int
     */
    public $partId;

    public function __construct($courseId, $partId)
    {
        $this->courseId = $courseId;
        $this->partId = $partId;
    }

    /**
     * @return int
     */
    public function getCourseId()
    {
        return $this->courseId;
    }

    /**
     * @return int
     */
    public function getPartId()
    {
        return $this->partId;
    }
}
