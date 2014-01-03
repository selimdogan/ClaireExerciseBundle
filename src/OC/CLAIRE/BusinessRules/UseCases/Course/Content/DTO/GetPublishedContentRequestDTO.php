<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Content\DTO;

use OC\CLAIRE\BusinessRules\Requestors\Course\Content\GetPublishedContentRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetPublishedContentRequestDTO implements GetPublishedContentRequest
{
    /**
     * @var int|string
     */
    private $courseIdentifier;

    public function __construct($courseIdentifier)
    {
        $this->courseIdentifier = $courseIdentifier;
    }

    /**
     * @return int|string
     */
    public function getCourseIdentifier()
    {
        return $this->courseIdentifier;
    }
}
