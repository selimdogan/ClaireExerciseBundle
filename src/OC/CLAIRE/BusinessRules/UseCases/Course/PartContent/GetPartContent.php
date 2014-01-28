<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\PartContent;

use OC\CLAIRE\BusinessRules\Gateways\Course\Part\PartContentGateway;
use OC\CLAIRE\BusinessRules\Requestors\UseCase;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
abstract class GetPartContent implements UseCase
{
    /**
     * @var PartContentGateway
     */
    protected $partContentGateway;

    public function setPartContentGateway(PartContentGateway $partContentGateway)
    {
        $this->partContentGateway = $partContentGateway;
    }
}
