<?php

namespace SimpleIT\ClaireAppBundle\UseCases\Course\Toc;

use SimpleIT\ApiResourcesBundle\Course\PartResource;
use SimpleIT\ClaireAppBundle\Gateways\Course\Toc\TocByCourseGateway;

/**
 * Class TocByCourseRepositoryStub
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class TocByCourseWithEmptyPartRepositoryStub extends TocByCourseRepositoryStub
{
    public function findByStatus($courseId, $status)
    {
        return new EmptyPartTocStub();
    }
}
