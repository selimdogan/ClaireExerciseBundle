<?php

namespace OC\CLAIRE\BusinessRules\Gateways\Course\Course;

use OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException;
use SimpleIT\ApiResourcesBundle\Course\CourseResource;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CourseNotFoundCourseGatewayStub implements CourseGateway
{
    /**
     * @return CourseResource[]
     */
    public function findAllStatus($courseIdentifier)
    {
        throw new CourseNotFoundException();
    }

    /**
     * @return CourseResource
     */
    public function findPublished($courseIdentifier)
    {
        throw new CourseNotFoundException();
    }

    /**
     * @return CourseResource
     */
    public function findWaitingForPublication($courseId)
    {
        throw new CourseNotFoundException();
    }

    /**
     * @return CourseResource
     */
    public function findDraft($courseId)
    {
        throw new CourseNotFoundException();
    }

    public function updateToWaitingForPublication($courseId)
    {
        throw new CourseNotFoundException();
    }

    public function updateDraftToPublished($courseId)
    {
        throw new CourseNotFoundException();
    }

    public function updateWaitingForPublicationToPublished($courseId)
    {
        throw new CourseNotFoundException();
    }

    public function updateDraft($courseId, CourseResource $course)
    {
        throw new CourseNotFoundException();
    }

    public function deleteWaitingForPublication($courseId)
    {
        throw new CourseNotFoundException();
    }

}
