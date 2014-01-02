<?php

namespace OC\BusinessRules\Requestors\Course\Content;

use OC\BusinessRules\Requestors\UseCaseRequest;

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
