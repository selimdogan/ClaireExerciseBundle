<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\CourseDifficulty\DTO;

use OC\CLAIRE\BusinessRules\Requestors\Course\CourseDifficulty\SaveCourseDifficultyRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class SaveCourseDifficultyRequestDTO implements SaveCourseDifficultyRequest
{
    /**
     * @var int
     */
    public $courseId;

    /**
     * @var string
     */
    public $difficulty;

    public function __construct($courseId, $difficulty)
    {
        $this->courseId = $courseId;
        $this->difficulty = $difficulty;
    }

    /**
     * @return int
     */
    public function getCourseId()
    {
        return $this->courseId;
    }

    /**
     * @return string
     */
    public function getDifficulty()
    {
        return $this->difficulty;
    }
}
