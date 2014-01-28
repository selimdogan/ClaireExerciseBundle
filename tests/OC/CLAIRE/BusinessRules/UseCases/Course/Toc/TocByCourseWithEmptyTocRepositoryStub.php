<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Toc;

/**
 * Class TocByCourseRepositoryStub
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class TocByCourseWithEmptyTocRepositoryStub extends TocByCourseRepositoryStub
{
    public function findByStatus($courseId, $status)
    {
        return new EmptyTocStub();
    }
}
