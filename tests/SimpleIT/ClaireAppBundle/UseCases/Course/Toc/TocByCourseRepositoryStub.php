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
    /**
     * @var PartResource
     */
    private $toc;

    public function findByStatus($courseId, $status)
    {
        return new TocStub1();
    }

    public function update($courseId, PartResource $toc)
    {
        $this->toc = $toc;
        $this->updateTocElement($this->toc);

        return $toc;
    }

    public function updateTocElement(PartResource $parent)
    {
        if (is_null($parent->getId())) {
            $parent->setId(999);
        } else {
            foreach ($parent->getChildren() as $child) {
                $this->updateTocElement($child);
            }
        }
    }
}
