<?php

namespace OC\CLAIRE\BusinessRules\Responders\Course\PartDifficulty;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface GetDraftPartDifficultyResponse
{
    /**
     * @return string
     */
    public function getDifficulty();
}
