<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\PartContent\DTO;

use OC\CLAIRE\BusinessRules\Responders\Course\PartContent\GetPartContentResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetPartContentResponseDTO implements GetPartContentResponse
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
