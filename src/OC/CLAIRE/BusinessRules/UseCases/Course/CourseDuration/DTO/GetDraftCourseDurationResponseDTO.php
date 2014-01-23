<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\CourseDuration\DTO;

use OC\CLAIRE\BusinessRules\Responders\Course\CourseDuration\GetDraftCourseDurationResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftCourseDurationResponseDTO implements GetDraftCourseDurationResponse
{
    /**
     * @var \DateInterval
     */
    public $courseDuration;

    public function __construct($courseDuration)
    {
        $this->courseDuration = $courseDuration;
    }

    /**
     * @return \DateInterval
     */
    public function getCourseDuration()
    {
        return $this->courseDuration;
    }
}
