<?php

namespace OC\BusinessRules\Responders\Course\Content;

use OC\BusinessRules\Responders\UseCaseResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface SaveContentResponse extends UseCaseResponse
{
    /**
     * @return string
     */
    public function getContent();
}
