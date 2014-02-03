<?php

namespace OC\CLAIRE\BusinessRules\Requestors;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface UseCaseFactory
{
    /**
     * @return UseCase
     */
    public function make($useCaseName);
}
