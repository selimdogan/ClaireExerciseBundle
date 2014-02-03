<?php

namespace OC\CLAIRE\BusinessRules\Responders\Course\Part;

use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface GetPartResponse extends UseCaseResponse
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
     * @return string
     */
    public function getDuration();

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
    public function getSubtype();

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @return \DateTime
     */
    public function getUpdatedAt();
}
