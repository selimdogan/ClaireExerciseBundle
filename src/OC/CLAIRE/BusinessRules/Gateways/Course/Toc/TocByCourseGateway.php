<?php

namespace OC\CLAIRE\BusinessRules\Gateways\Course\Toc;

use SimpleIT\ApiResourcesBundle\Course\CourseResource;
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

    /**
     * @return PartResource
     */
    public function update($courseId, PartResource $toc);

    /**
     * @return CourseResource
     */
    public function findDraft($courseId);

    /**
     * @return PartResource
     */
    public function findWaitingForPublication($courseId);

    /**
     * @return PartResource
     */
    public function findPublished($courseIdentifier);
}
