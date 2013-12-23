<?php

namespace SimpleIT\ClaireAppBundle\Requestors\Course\Content;

use SimpleIT\ClaireAppBundle\Requestors\UseCaseRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface GetWaitingForPublicationContentRequest extends UseCaseRequest
{
    /**
     * @return int
     */
    public function getCourseId();
}
