<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\DisplayLevel\DTO;

use OC\CLAIRE\BusinessRules\Responders\Course\DisplayLevel\GetDraftDisplayLevelResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftDisplayLevelResponseDTO implements GetDraftDisplayLevelResponse
{
    /**
     * @var int
     */
    public $displayLevel;

    public function __construct($displayLevel)
    {
        $this->displayLevel = $displayLevel;
    }

    /**
     * @return int
     */
    public function getDisplayLevel()
    {
        return $this->displayLevel;
    }
}
