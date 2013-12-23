<?php

namespace SimpleIT\ClaireAppBundle\Resources\config;

use SimpleIT\ClaireAppBundle\Util\KernelForTest;
use Symfony\Component\HttpKernel\Kernel;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class ContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Kernel
     */
    private $kernel;

    /**
     * @test
     */
    public function BootKernel_InitializeContainer()
    {
        $this->kernel = new KernelForTest();
        $this->kernel->registerBundles();
        $this->kernel->boot();
    }

    protected function tearDown()
    {
        $this->kernel->shutdown();
        parent::tearDown();
    }

}
