<?php

namespace OC\CLAIRE\BusinessRules\Requestors\Course\Part;

use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface SavePartRequest extends UseCaseRequest
{
    /**
     * @return int
     */
    public function getCourseId();

    /**
     * @return string
     */
    public function getPartDescription();

    /**
     * @return string
     */
    public function getPartDifficulty();

    /**
     * @return string
     */
    public function getPartDuration();

    /**
     * @return int
     */
    public function getPartId();

    /**
     * @return string
     */
    public function getPartTitle();
}
