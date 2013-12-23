<?php

namespace SimpleIT\ClaireAppBundle\Resources\config;

use SimpleIT\ClaireAppBundle\Util\KernelForTest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class ContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function BootKernel_InitializeContainer()
    {
        $kernel = new KernelForTest();
        $kernel->registerBundles();
        $kernel->boot();
    }
}
