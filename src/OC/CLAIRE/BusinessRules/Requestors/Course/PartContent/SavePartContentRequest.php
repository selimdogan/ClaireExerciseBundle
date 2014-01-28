<?php

namespace OC\CLAIRE\BusinessRules\Requestors\Course\PartContent;

use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface SavePartContentRequest extends UseCaseRequest
{
    /**
     * @return int
     */
    public function getCourseId();

    /**
     * @return int
     */
    public function getPartId();

    /**
     * @return string
     */
    public function getContent();
}
