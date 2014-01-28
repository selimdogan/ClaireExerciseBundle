<?php

namespace OC\CLAIRE\BusinessRules\Requestors\Course\PartDescription;

use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface SavePartDescriptionRequest extends UseCaseRequest
{
    /**
     * @return int
     */
    public function getCourseId();

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @return int
     */
    public function getPartId();
}
