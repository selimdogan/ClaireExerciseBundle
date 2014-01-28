<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\DisplayLevel\DTO;

use OC\CLAIRE\BusinessRules\Requestors\Course\DisplayLevel\GetDraftDisplayLevelRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftDisplayLevelRequestDTO implements GetDraftDisplayLevelRequest
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
