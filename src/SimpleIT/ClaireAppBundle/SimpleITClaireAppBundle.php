<?php

namespace SimpleIT\ClaireAppBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use SimpleIT\ClaireAppBundle\DependencyInjection\Compiler\TransportCompilerPass;

/**
 * Claire App Bundle
 */
class SimpleITClaireAppBundle extends Bundle
{
    public function getParent()
    {
        return 'SimpleITAppBundle';
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new TransportCompilerPass());
    }

}
