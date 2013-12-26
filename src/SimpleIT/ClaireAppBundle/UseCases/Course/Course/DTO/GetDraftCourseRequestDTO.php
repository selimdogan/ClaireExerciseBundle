<?php

namespace SimpleIT\ClaireAppBundle\UseCases\Course\Course\DTO;

use SimpleIT\ClaireAppBundle\Requestors\Course\Content\GetDraftContentRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftCourseRequestDTO implements GetDraftContentRequest
{
    /**
     * @var int
     */
    public $courseId;

    public function __construct($courseId)
    {
        $this->courseId = $courseId;
    }

    /**
     * @return int
     */
    public function getCourseId()
    {
        return $this->courseId;
    }
}
