<?php

namespace OC\CLAIRE\BusinessRules\Responders\Course\PartDescription;

use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface GetDraftPartDescriptionResponse extends UseCaseResponse
{
    /**
     * @return string
     */
    public function getDescription();
}
