<?php

namespace OC\CLAIRE\BusinessRules\Requestors\Course\Part;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface GetDraftPartRequest extends GetPartRequest
{
    /**
     * @return int
     */
    public function getCourseId();

    /**
     * @return int
     */
    public function getPartId();
}
