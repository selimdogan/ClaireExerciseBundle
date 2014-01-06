<?php

namespace OC\CLAIRE\BusinessRules\Gateways\Course\Course;

use OC\CLAIRE\BusinessRules\Entities\Course\DraftCourseStub;
use OC\CLAIRE\BusinessRules\Entities\Course\PublishedCourseStub;
use OC\CLAIRE\BusinessRules\Entities\Course\WaitingForPublicationCourseStub;
use SimpleIT\ApiResourcesBundle\Course\CourseResource;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CourseGatewayStub implements CourseGateway
{
    /**
     * @return CourseResource[]
     */
    public function findAllStatus($courseIdentifier)
    {
        return array(
            new PublishedCourseStub(),
            new WaitingForPublicationCourseStub(),
            new DraftCourseStub()
        );
    }

    /**
     * @return CourseResource
     */
    public function findPublished($courseIdentifier)
    {
        return new PublishedCourseStub();
    }

    /**
     * @return CourseResource
     */
    public function findWaitingForPublication($courseId)
    {
        return new WaitingForPublicationCourseStub();
    }

    /**
     * @return CourseResource
     */
    public function findDraft($courseId)
    {
        return new DraftCourseStub();
    }

    public function updateToWaitingForPublication($courseId)
    {
        return null;
    }

    public function updateToPublished($courseId)
    {
        return null;
    }

    public function updateDraft($courseId, CourseResource $course)
    {
        return null;
    }
}
