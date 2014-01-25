<?php

namespace Resources\config;

use OC\CLAIRE\BusinessRules\Util\KernelForTest;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Router;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class RoutingTest //extends \PHPUnit_Framework_TestCase
{
    const ROUTING_FILE = 'routing.yml';

    protected $kernel;

    /**
     * @test
     */
    public function tot()
    {
        $this->kernel = new KernelForTest();
        $this->kernel->boot();
        $router = $this->kernel->getContainer()->get('router');
    }

    /**
     * @test
     */
    public function RouterValidation()
    {
        $router = new Router(
            new YamlFileLoader(new FileLocator(array('/Users/romain/Developpement/GIT_REPO/ClaireAppBundle/src/SimpleIT/ClaireAppBundle/Resources/config/routing'))),
            self::ROUTING_FILE
        );

        $routes = $router->getRouteCollection();
        $routes->all();

    }
}
