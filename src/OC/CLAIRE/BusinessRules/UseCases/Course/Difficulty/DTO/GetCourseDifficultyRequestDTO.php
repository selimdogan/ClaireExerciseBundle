<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Difficulty\DTO;

use OC\CLAIRE\BusinessRules\Requestors\Course\Difficulty\GetCourseDifficultyRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetCourseDifficultyRequestDTO implements GetCourseDifficultyRequest
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
