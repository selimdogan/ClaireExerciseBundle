<?php

namespace SimpleIT\ClaireAppBundle\Requestors\Course\Content;

use SimpleIT\ClaireAppBundle\Requestors\UseCaseRequest;

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
