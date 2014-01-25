<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Part\DTO;

use OC\CLAIRE\BusinessRules\Requestors\Course\Part\SavePartRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class SavePartRequestDTO implements SavePartRequest
{
    /**
     * @var int
     */
    public $courseId;

    /**
     * @var int
     */
    public $partId;

    /**
     * @var string
     */
    public $partDescription;

    /**
     * @var string
     */
    public $partDifficulty;

    /**
     * @var string
     */
    public $partDuration;

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
    public function getPartDescription()
    {
        return $this->partDescription;
    }

    /**
     * @return string
     */
    public function getPartDifficulty()
    {
        return $this->partDifficulty;
    }

    /**
     * @return string
     */
    public function getPartDuration()
    {
        return $this->partDuration;
    }

    /**
     * @return int
     */
    public function getPartId()
    {
        return $this->partId;
    }
}
