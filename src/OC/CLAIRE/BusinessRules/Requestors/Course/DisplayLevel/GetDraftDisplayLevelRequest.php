<?php

namespace OC\CLAIRE\BusinessRules\Requestors\Course\DisplayLevel;

use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface GetDraftDisplayLevelRequest extends UseCaseRequest
{
    /**
     * @return int
     */
    public function getCourseId();

}
