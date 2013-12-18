<?php

namespace SimpleIT\ClaireAppBundle\UseCases\Course\Toc;

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
