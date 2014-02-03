<?php

namespace OC\CLAIRE\BusinessRules\Requestors\Course\PartContent;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface GetWaitingForPublicationPartContentRequest extends GetPartContentRequest
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
