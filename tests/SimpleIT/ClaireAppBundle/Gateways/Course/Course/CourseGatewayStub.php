<?php

namespace SimpleIT\ClaireAppBundle\Gateways\Course\Course;

use SimpleIT\ApiResourcesBundle\Course\CourseResource;
use SimpleIT\ClaireAppBundle\Entities\Course\DraftCourseStub;
use SimpleIT\ClaireAppBundle\Entities\Course\PublishedCourseStub;
use SimpleIT\ClaireAppBundle\Entities\Course\WaitingForPublicationCourseStub;

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

}
