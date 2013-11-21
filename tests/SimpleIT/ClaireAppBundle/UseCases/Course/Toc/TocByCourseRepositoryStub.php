<?php

namespace SimpleIT\ClaireAppBundle\UseCases\Course\Toc;

use SimpleIT\ApiResourcesBundle\Course\PartResource;
use SimpleIT\ClaireAppBundle\Gateways\Course\Toc\TocByCourseGateway;

/**
 * Class TocByCourseRepositoryStub
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class TocByCourseRepositoryStub implements TocByCourseGateway
{
    public function findByStatus($courseId, $status)
    {
        return new TocStub1();
    }

    public function update($courseId, PartResource $toc)
    {
        return $toc;
    }

}
