<?php

namespace SimpleIT\ClaireAppBundle\Responders\Course\Content;

use SimpleIT\ClaireAppBundle\Responders\UseCaseResponse;

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
