<?php

namespace OC\CLAIRE\BusinessRules\Requestors\Course\PartDescription;

use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface GetDraftPartDescriptionRequest extends UseCaseRequest
{
    /**
     * @return int
     */
    public function getCourseId();

    /**
     * @return int
     */
    public function getPartId();
}
