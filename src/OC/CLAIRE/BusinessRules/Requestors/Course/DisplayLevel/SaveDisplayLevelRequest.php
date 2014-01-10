<?php

namespace OC\CLAIRE\BusinessRules\Requestors\Course\DisplayLevel;

use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface SaveDisplayLevelRequest extends UseCaseRequest
{
    /**
     * @return int
     */
    public function getCourseId();

    /**
     * @return int
     */
    public function getDisplayLevel();
}
