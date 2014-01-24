<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\PartDescription\DTO;

use OC\CLAIRE\BusinessRules\Requestors\Course\PartDescription\SavePartDescriptionRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class SavePartDescriptionRequestDTO implements SavePartDescriptionRequest
{
    /**
     * @var int
     */
    public $courseId;

    /**
     * @var string
     */
    public $description;

    /**
     * @var int
     */
    public $partId;

    public function __construct($courseId, $partId, $description)
    {
        $this->courseId = $courseId;
        $this->partId = $partId;
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getCourseId()
    {
        return $this->courseId;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getPartId()
    {
        return $this->partId;
    }
}
