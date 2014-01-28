<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\PartDescription\DTO;

use OC\CLAIRE\BusinessRules\Responders\Course\PartDescription\GetDraftPartDescriptionResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftPartDescriptionResponseDTO implements GetDraftPartDescriptionResponse
{
    /**
     * @var string
     */
    public $description;

    public function __construct($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
