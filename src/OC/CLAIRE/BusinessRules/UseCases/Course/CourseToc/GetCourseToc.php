<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\CourseToc;

use OC\CLAIRE\BusinessRules\Gateways\Course\Toc\TocByCourseGateway;
use OC\CLAIRE\BusinessRules\Requestors\UseCase;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
abstract class GetCourseToc implements UseCase
{
    /**
     * @var TocByCourseGateway
     */
    protected $tocByCourseGateway;

    public function setTocByCourseGateway(TocByCourseGateway $tocByCourseGateway)
    {
        $this->tocByCourseGateway = $tocByCourseGateway;
    }
}
