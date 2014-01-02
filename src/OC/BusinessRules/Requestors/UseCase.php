<?php

namespace OC\BusinessRules\Requestors;

/**
 * Interface UseCase
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
interface UseCase
{
    /**
     * @return \OC\BusinessRules\Responders\UseCaseResponse
     */
    public function execute(UseCaseRequest $useCaseRequest);
}
