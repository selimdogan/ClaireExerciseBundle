<?php

namespace OC\CLAIRE\BusinessRules\Gateways\Course\Toc;

use OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException;
use SimpleIT\ApiResourcesBundle\Course\PartResource;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CourseNotFoundTocByCourseGatewayStub implements TocByCourseGateway
{
    /**
     * @return PartResource
     */
    public function findByStatus($courseId, $status)
    {
        throw new CourseNotFoundException();
    }

    /**
     * @return PartResource
     */
    public function update($courseId, PartResource $toc)
    {
        throw new CourseNotFoundException();
    }

    /**
     * @return PartResource
     */
    public function findDraft($courseId)
    {
        throw new CourseNotFoundException();
    }

    /**
     * @return PartResource
     */
    public function findWaitingForPublication($courseId)
    {
        throw new CourseNotFoundException();
    }

    /**
     * @return PartResource
     */
    public function findPublished($courseIdentifier)
    {
        throw new CourseNotFoundException();
    }

}
