<?php

namespace SimpleIT\ClaireAppBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Description of BuzzCompilerPass
 *
 * @author Kévin Letord <kevin.letord@simple-it.fr>
 */
class TransportCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('simple_it.claire.http.transport')) {
            return;
        }

        $definition = $container->getDefinition(
            'simple_it.claire.http.transport'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'simple_it.claire.http.transport.listener'
        );

        foreach ($taggedServices as $id => $attributes) {
            $definition->addMethodCall(
                'addListener',
                array(new Reference($id))
            );
        }
    }
}
