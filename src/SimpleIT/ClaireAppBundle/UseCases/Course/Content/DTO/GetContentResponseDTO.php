<?php

namespace SimpleIT\ClaireAppBundle\UseCases\Course\Content\DTO;

use SimpleIT\ClaireAppBundle\Responders\Course\Content\GetContentResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetContentResponseDTO implements GetContentResponse
{
    /**
     * @var string
     */
    public $content;

    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
}
