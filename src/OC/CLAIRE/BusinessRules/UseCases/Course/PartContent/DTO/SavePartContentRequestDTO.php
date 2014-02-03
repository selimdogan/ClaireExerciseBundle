<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\PartContent\DTO;

use OC\CLAIRE\BusinessRules\Requestors\Course\PartContent\SavePartContentRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class SavePartContentRequestDTO implements SavePartContentRequest
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
    public $content;

    public function __construct($courseId, $partId, $content)
    {
        $this->courseId = $courseId;
        $this->partId = $partId;
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
     * @return int
     */
    public function getPartId()
    {
        return $this->partId;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
}
