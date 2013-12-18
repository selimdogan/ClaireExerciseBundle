<?php

namespace SimpleIT\ClaireAppBundle\Requestors\Course\Workflow;

use SimpleIT\ClaireAppBundle\Requestors\UseCaseRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface ChangeCourseStatusRequest extends UseCaseRequest
{
    /**
     * @return int
     */
    public function getCourseId();
}
