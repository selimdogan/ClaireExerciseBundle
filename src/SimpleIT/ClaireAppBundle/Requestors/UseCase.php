<?php

namespace SimpleIT\ClaireAppBundle\Requestors;

use SimpleIT\ClaireAppBundle\Responders\UseCaseResponse;

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
