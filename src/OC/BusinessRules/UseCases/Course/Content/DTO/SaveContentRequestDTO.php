<?php

namespace OC\BusinessRules\UseCases\Course\Content\DTO;

use OC\BusinessRules\Requestors\Course\Content\SaveContentRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class SaveContentRequestDTO implements SaveContentRequest
{
    /**
     * @var int
     */
    public $courseId;

    /**
     * @var string
     */
    public $content;

    public function __construct($courseId, $content)
    {
        $this->courseId = $courseId;
        $this->content = $content;
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
    public function getContent()
    {
        return $this->content;
    }
}
