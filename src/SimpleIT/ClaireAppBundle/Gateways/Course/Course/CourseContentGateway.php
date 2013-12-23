<?php

namespace SimpleIT\ClaireAppBundle\Gateways\Course\Course;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface CourseContentGateway
{
    /**
     * @return string
     */
    public function findPublished($courseIdentifier);

    /**
     * @return string
     */
    public function findWaitingForPublication($courseId);

    /**
     * @return string
     */
    public function findDraft($courseId);

    /**
     * @return string
     */
    public function update($courseId, $content);
}
