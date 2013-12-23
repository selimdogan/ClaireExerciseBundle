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
    public function findPublished();

    /**
     * @return string
     */
    public function findWaitingForPublication();

    /**
     * @return string
     */
    public function findDraft();

    /**
     * @return string
     */
    public function update($courseId, $content);
}
