<?php

namespace OC\CLAIRE\BusinessRules\Gateways\Course\Course;

use OC\CLAIRE\BusinessRules\Entities\Course\SmallEasyDraftCourseStub;
use SimpleIT\ApiResourcesBundle\Course\CourseResource;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CourseGatewaySpy implements CourseGateway
{
    /**
     * @var int
     */
    public $courseId;

    /**
     * @var CourseResource
     */
    public $course;

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
        return new SmallEasyDraftCourseStub();
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
        $this->courseId = $courseId;
        $this->course = $course;
    }

}
