<?php

namespace OC\CLAIRE\BusinessRules\Requestors;

use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;

/**
 * Interface UseCase
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
interface UseCase
{
    /**
     * @return UseCaseResponse
     */
    public function execute(UseCaseRequest $useCaseRequest);
}
