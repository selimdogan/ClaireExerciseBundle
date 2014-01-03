<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Toc;

/**
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class TocByCourseWithEmptyPartRepositoryStub extends TocByCourseRepositoryStub
{
    public function findByStatus($courseId, $status)
    {
        return new EmptyPartTocStub();
    }
}
