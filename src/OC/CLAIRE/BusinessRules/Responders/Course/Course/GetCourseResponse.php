<?php

namespace OC\CLAIRE\BusinessRules\Responders\Course\Course;

use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface GetCourseResponse extends UseCaseResponse
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getSlug();

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @return int
     */
    public function getDisplayLevel();

    /**
     * @return string
     */
    public function getStatus();

    /**
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * @return \DateTime
     */
    public function getUpdatedAt();
}
