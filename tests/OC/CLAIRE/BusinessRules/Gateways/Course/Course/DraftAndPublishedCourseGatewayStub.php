<?php

namespace OC\CLAIRE\BusinessRules\Gateways\Course\Course;

use OC\CLAIRE\BusinessRules\Entities\Course\Course\DraftCourseStub;
use OC\CLAIRE\BusinessRules\Entities\Course\Course\PublishedCourseStub;
use SimpleIT\ApiResourcesBundle\Course\CourseResource;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class DraftAndPublishedCourseGatewayStub implements CourseGateway
{
    /**
     * @return CourseResource[]
     */
    public function findAllStatus($courseIdentifier)
    {
        return array(new DraftCourseStub(), new PublishedCourseStub());
    }

    /**
     * @return CourseResource
     */
    public function findDraft($courseId)
    {
        return new DraftCourseStub();
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
    public function findPublished($courseIdentifier)
    {
        return new PublishedCourseStub();
    }

    public function updateToWaitingForPublication($courseId)
    {
        return null;
    }

    public function updateWaitingForPublicationToPublished($courseId)
    {
        return null;
    }

    public function updateDraftToPublished($courseId)
    {
        return null;
    }

    public function updateDraft($courseId, CourseResource $course)
    {
        return null;
    }

}
