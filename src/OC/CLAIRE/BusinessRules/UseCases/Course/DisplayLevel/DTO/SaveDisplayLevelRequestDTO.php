<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\DisplayLevel\DTO;

use OC\CLAIRE\BusinessRules\Requestors\Course\DisplayLevel\SaveDisplayLevelRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class SaveDisplayLevelRequestDTO implements SaveDisplayLevelRequest
{
    /**
     * @var int
     */
    public $courseId;

    /**
     * @var int
     */
    public $displayLevel;

    public function __construct($courseId, $displayLevel)
    {
        $this->courseId = $courseId;
        $this->displayLevel = $displayLevel;
    }

    /**
     * @return int
     */
    public function getCourseId()
    {
        return $this->courseId;
    }

    /**
     * @return int
     */
    public function getDisplayLevel()
    {
        return $this->displayLevel;
    }
}
