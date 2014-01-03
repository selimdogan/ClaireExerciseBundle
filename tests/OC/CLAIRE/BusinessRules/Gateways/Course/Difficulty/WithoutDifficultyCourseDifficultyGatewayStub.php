<?php

namespace OC\CLAIRE\BusinessRules\Gateways\Course\Difficulty;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class WithoutDifficultyCourseDifficultyGatewayStub implements CourseDifficultyGateway
{
    /**
     * @return string
     */
    public function find($courseId)
    {
        return null;
    }

    public function update($courseId, $difficulty)
    {
        return null;
    }

}
