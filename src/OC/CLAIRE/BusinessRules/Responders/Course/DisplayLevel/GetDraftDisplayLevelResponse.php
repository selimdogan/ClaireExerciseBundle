<?php

namespace OC\CLAIRE\BusinessRules\Responders\Course\DisplayLevel;

use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface GetDraftDisplayLevelResponse extends UseCaseResponse
{
    /**
     * @return int
     */
    public function getDisplayLevel();
}
