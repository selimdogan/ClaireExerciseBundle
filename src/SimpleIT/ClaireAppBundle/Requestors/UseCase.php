<?php

namespace SimpleIT\ClaireAppBundle\Requestors;

/**
 * Interface UseCase
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
interface UseCase
{
    public function execute(UseCaseRequest $useCaseRequest);
} 
