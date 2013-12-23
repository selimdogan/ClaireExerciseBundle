<?php

namespace SimpleIT\ClaireAppBundle\Responders\Course\Content;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface GetContentResponse
{
    /**
     * @return string
     */
    public function getContent();
}
