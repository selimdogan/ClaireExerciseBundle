<?php

namespace OC\CLAIRE\BusinessRules\Gateways\Course\Course;

use SimpleIT\ApiResourcesBundle\Course\CourseResource;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class BigHardCourseGatewayStub implements CourseGateway
{
    /**
     * @return CourseResource[]
     */
    public function findAllStatus($courseIdentifier)
    {
        return null;
    }

    /**
     * @return CourseResource
     */
    public function findPublished($courseIdentifier)
    {
        return null;
    }

    /**
     * @return CourseResource
     */
    public function findWaitingForPublication($courseId)
    {
        return null;
    }

    /**
     * @return CourseResource
     */
    public function findDraft($courseId)
    {
        return new \OC\CLAIRE\BusinessRules\Entities\Course\Course\BigHardDraftCourseStub();
    }

    public function updateToWaitingForPublication($courseId)
    {
    }

    public function updateDraftToPublished($courseId)
    {
    }

    public function updateWaitingForPublicationToPublished($courseId)
    {
    }

    public function updateDraft($courseId, CourseResource $course)
    {
    }

}
