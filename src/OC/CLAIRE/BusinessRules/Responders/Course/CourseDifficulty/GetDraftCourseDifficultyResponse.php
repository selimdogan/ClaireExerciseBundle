<?php

namespace OC\CLAIRE\BusinessRules\Responders\Course\CourseDifficulty;

use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface GetDraftCourseDifficultyResponse extends UseCaseResponse
{
    /**
     * @return string
     */
    public function getDifficulty();
}
