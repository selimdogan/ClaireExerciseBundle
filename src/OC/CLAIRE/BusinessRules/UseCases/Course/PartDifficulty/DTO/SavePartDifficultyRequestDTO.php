<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\PartDifficulty\DTO;

use OC\CLAIRE\BusinessRules\Requestors\Course\PartDifficulty\SavePartDifficultyRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class SavePartDifficultyRequestDTO implements SavePartDifficultyRequest
{
    /**
     * @var int
     */
    public $courseId;

    /**
     * @var int
     */
    public $partId;

    /**
     * @var string
     */
    public $difficulty;

    public function __construct($courseId, $partId, $difficulty)
    {
        $this->courseId = $courseId;
        $this->partId = $partId;
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

    /**
     * @return int
     */
    public function getPartId()
    {
        return $this->partId;
    }
}
