<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\PartContent\DTO;

use OC\CLAIRE\BusinessRules\Requestors\Course\PartContent\GetPublishedPartContentRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetPublishedPartContentRequestDTO implements GetPublishedPartContentRequest
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
     * @return int
     */
    public function getCourseIdentifier()
    {
        return $this->courseIdentifier;
    }

    /**
     * @return int
     */
    public function getPartIdentifier()
    {
        return $this->partIdentifier;
    }
}
