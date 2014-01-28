<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\PartDifficulty\DTO;

use OC\CLAIRE\BusinessRules\Responders\Course\PartDifficulty\GetDraftPartDifficultyResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftPartDifficultyResponseDTO implements GetDraftPartDifficultyResponse
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
