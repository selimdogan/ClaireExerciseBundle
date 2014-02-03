<?php

namespace OC\CLAIRE\BusinessRules\Requestors\Course\PartContent;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface GetPublishedPartContentRequest extends GetPartContentRequest
{
    /**
     * @return int|string
     */
    public function getCourseIdentifier();

    /**
     * @return int|string
     */
    public function getPartIdentifier();
}
