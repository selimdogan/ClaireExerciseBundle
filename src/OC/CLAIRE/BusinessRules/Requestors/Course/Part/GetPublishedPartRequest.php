<?php

namespace OC\CLAIRE\BusinessRules\Requestors\Course\Part;

use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface GetPublishedPartRequest extends UseCaseRequest
{
    /**
     * @return int|string
     */
    public function getCourseIdentifier();

    /**
     * @return int|string
     */
    public function getPartIdentifier();
}
