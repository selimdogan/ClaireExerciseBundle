<?php

namespace OC\CLAIRE\BusinessRules\Gateways\Course\Course;

use OC\CLAIRE\BusinessRules\Entities\Course\Course\PublishedCourseStub;
use OC\CLAIRE\BusinessRules\Entities\Course\Course\SmallEasyDraftCourseStub;
use OC\CLAIRE\BusinessRules\Entities\Course\Course\WaitingForPublicationCourseStub;
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
            new SmallEasyDraftCourseStub()
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
        return new SmallEasyDraftCourseStub();
    }

    public function updateToWaitingForPublication($courseId)
    {
        return null;
    }

    public function updateDraftToPublished($courseId)
    {
    }

    public function updateWaitingForPublicationToPublished($courseId)
    {
        return null;
    }

    public function updateDraft($courseId, CourseResource $course)
    {
        return null;
    }

    public function deleteWaitingForPublication($courseId)
    {
        return null;
    }
}
