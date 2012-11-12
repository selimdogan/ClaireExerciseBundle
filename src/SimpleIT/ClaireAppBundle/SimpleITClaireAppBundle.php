<?php

namespace SimpleIT\ClaireAppBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Claire App Bundle
 */
class SimpleITClaireAppBundle extends Bundle
{
    public function getParent()
    {
        return 'SimpleITAppBundle';
    }

}
