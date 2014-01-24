<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\PartDescription\DTO;

use OC\CLAIRE\BusinessRules\Requestors\Course\PartDescription\GetDraftPartDescriptionRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftPartDescriptionRequestDTO implements GetDraftPartDescriptionRequest
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
