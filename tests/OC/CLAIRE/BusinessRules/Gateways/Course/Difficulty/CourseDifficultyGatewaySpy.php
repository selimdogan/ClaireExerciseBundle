<?php

namespace OC\CLAIRE\BusinessRules\Gateways\Course\Difficulty;

use OC\CLAIRE\BusinessRules\Entities\Difficulty\Difficulty;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CourseDifficultyGatewaySpy implements CourseDifficultyGateway
{
    /**
     * @var int
     */
    public $updatedCourseId;

    /**
     * @var string
     */
    public $updatedDifficulty;

    /**
     * @return string
     */
    public function findDraft($courseId)
    {
        return Difficulty::EASY;
    }

    public function update($courseId, $difficulty)
    {
        $this->updatedCourseId = $courseId;
        $this->updatedDifficulty = $difficulty;
    }

}
