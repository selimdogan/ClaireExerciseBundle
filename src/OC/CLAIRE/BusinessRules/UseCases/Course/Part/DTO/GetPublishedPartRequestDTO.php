<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Part\DTO;

use OC\CLAIRE\BusinessRules\Requestors\Course\Part\GetPublishedPartRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetPublishedPartRequestDTO implements GetPublishedPartRequest
{
    /**
     * @var int|string
     */
    public $courseIdentifier;

    /**
     * @var int|string
     */
    public $partIdentifier;

    public function __construct($courseIdentifier, $partIdentifier)
    {
        $this->courseIdentifier = $courseIdentifier;
        $this->partIdentifier = $partIdentifier;
    }

    /**
     * @return int|string
     */
    public function getCourseIdentifier()
    {
        return $this->courseIdentifier;
    }

    /**
     * @return int|string
     */
    public function getPartIdentifier()
    {
        return $this->partIdentifier;
    }
}
