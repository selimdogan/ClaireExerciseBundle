<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Difficulty\DTO;

use OC\CLAIRE\BusinessRules\Responders\Course\Difficulty\GetCourseDifficultyResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetCourseDifficultyResponseDTO implements GetCourseDifficultyResponse
{
    /**
     * @var string
     */
    public $difficulty;

    public function __construct($difficulty)
    {
        $this->difficulty = $difficulty;
    }

    /**
     * @return string
     */
    public function getDifficulty()
    {
        return $this->difficulty;
    }
}
