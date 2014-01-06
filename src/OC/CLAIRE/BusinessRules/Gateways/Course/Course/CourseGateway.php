<?php

namespace OC\CLAIRE\BusinessRules\Gateways\Course\Course;

use SimpleIT\ApiResourcesBundle\Course\CourseResource;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface CourseGateway
{
    /**
     * @return CourseResource[]
     */
    public function findAllStatus($courseIdentifier);

    /**
     * @return CourseResource
     */
    public function findPublished($courseIdentifier);

    /**
     * @return CourseResource
     */
    public function findWaitingForPublication($courseId);

    /**
     * @return CourseResource
     */
    public function findDraft($courseId);

    public function updateToWaitingForPublication($courseId);

    public function updateToPublished($courseId);

    public function updateDraft($courseId, CourseResource $course);
}
