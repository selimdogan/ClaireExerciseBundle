<?php

namespace OC\CLAIRE\BusinessRules\Responders\Course\CourseDuration;

use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface GetDraftCourseDurationResponse extends UseCaseResponse
{
    /**
     * @return \DateInterval
     */
    public function getCourseDuration();
}
