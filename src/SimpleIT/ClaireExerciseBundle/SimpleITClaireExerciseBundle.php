<?php

namespace SimpleIT\ClaireExerciseBundle;

use Claroline\CoreBundle\Library\PluginBundle;
use Claroline\KernelBundle\Bundle\ConfigurationBuilder;

/**
 * Claire App Bundle
 */
class SimpleITClaireExerciseBundle extends PluginBundle
{
    public function getConfiguration($environment)
    {
        $config = new ConfigurationBuilder();

        return $config->addRoutingResource(
            __DIR__ . '/Resources/config/routing.yml',
            null,
            'claire_exercise'
        );
    }
}
