<?php

namespace OC\BusinessRules\Requestors\Course\Content;

use OC\BusinessRules\Requestors\UseCaseRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface GetPublishedContentRequest extends UseCaseRequest
{
    /**
     * @return int|string
     */
    public function getCourseIdentifier();
}
