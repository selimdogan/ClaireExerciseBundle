<?php

namespace OC\CLAIRE\BusinessRules\Gateways\Course\Difficulty;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface CourseDifficultyGateway
{
    /**
     * @return string
     */
    public function findDraft($courseId);

    public function update($courseId, $difficulty);
}
