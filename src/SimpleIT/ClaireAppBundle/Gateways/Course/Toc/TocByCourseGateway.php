<?php

namespace SimpleIT\ClaireAppBundle\Gateways\Course\Toc;

use SimpleIT\ApiResourcesBundle\Course\PartResource;

/**
 * Interface TocByCourseGateway
 * 
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
interface TocByCourseGateway
{
    /**
     * @return PartResource
     */
    public function findByStatus($courseId, $status);
}
