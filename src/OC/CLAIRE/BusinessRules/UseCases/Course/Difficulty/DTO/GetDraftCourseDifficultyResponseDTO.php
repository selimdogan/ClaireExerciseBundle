<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Difficulty\DTO;

use OC\CLAIRE\BusinessRules\Responders\Course\Difficulty\GetDraftCourseDifficultyResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftCourseDifficultyResponseDTO implements GetDraftCourseDifficultyResponse
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
