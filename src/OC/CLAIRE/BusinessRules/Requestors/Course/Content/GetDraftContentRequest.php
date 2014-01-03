<?php

namespace OC\CLAIRE\BusinessRules\Requestors\Course\Content;

use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface GetDraftContentRequest extends UseCaseRequest
{
    /**
     * @return int
     */
    public function getCourseId();
}
