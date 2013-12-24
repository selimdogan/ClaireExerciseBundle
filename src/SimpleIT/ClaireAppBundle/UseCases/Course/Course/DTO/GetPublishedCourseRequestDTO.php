<?php

namespace SimpleIT\ClaireAppBundle\UseCases\Course\Course\DTO;

use SimpleIT\ClaireAppBundle\Requestors\Course\Course\GetPublishedCourseRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetPublishedCourseRequestDTO implements GetPublishedCourseRequest
{
    /**
     * @var int|string
     */
    public $courseIdentifier;

    /**
     * @return int|string
     */
    public function getCourseIdentifier()
    {
        return $this->courseIdentifier;
    }
}
