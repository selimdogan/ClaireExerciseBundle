<?php

namespace OC\CLAIRE\BusinessRules\Gateways\Course\Part;

use OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException;
use SimpleIT\ApiResourcesBundle\Course\PartResource;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CourseNotFoundPartGateway implements PartGateway
{
    /**
     * @return PartResource
     */
    public function findDraft($courseId, $partId)
    {
        throw new CourseNotFoundException();
    }

    /**
     * @return PartResource
     */
    public function findWaitingForPublication($courseId, $partId)
    {
        throw new CourseNotFoundException();
    }

    /**
     * @return PartResource
     */
    public function findPublished($courseIdentifier, $partIdentifier)
    {
        throw new CourseNotFoundException();
    }

    public function updateDraft($courseId, $partId, PartResource $part)
    {
        throw new CourseNotFoundException();
    }
}
