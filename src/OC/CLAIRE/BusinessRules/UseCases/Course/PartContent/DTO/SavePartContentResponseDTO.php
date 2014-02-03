<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\PartContent\DTO;

use OC\CLAIRE\BusinessRules\Responders\Course\PartContent\SavePartContentResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class SavePartContentResponseDTO implements SavePartContentResponse
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
