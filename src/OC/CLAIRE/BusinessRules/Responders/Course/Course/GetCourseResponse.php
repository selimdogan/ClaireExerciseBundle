<?php

namespace OC\CLAIRE\BusinessRules\Responders\Course\Course;

use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface GetCourseResponse extends UseCaseResponse
{
    /**
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @return string
     */
    public function getDifficulty();

    /**
     * @return int
     */
    public function getDisplayLevel();

    /**
     * @return \DateInterval
     */
    public function getDuration();

    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getImage();

    /**
     * @return string
     */
    public function getLicense();

    /**
     * @return float
     */
    public function getRating();

    /**
     * @return mixed
     */
    public function getSlug();

    /**
     * @return string
     */
    public function getStatus();

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @return \DateTime
     */
    public function getUpdatedAt();
}
