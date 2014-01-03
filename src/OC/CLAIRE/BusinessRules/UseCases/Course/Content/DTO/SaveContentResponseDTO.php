<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Content\DTO;

use OC\CLAIRE\BusinessRules\Responders\Course\Content\SaveContentResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class SaveContentResponseDTO implements SaveContentResponse
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
