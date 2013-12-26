<?php

namespace SimpleIT\ClaireAppBundle\UseCases\Course\Course\DTO;

use SimpleIT\ClaireAppBundle\Requestors\Course\Course\GetWaitingForPublicationCourseRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetWaitingForPublicationCourseRequestDTO implements GetWaitingForPublicationCourseRequest
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
