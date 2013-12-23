<?php

namespace SimpleIT\ClaireAppBundle\Util;

use SimpleIT\AppBundle\SimpleITAppBundle;
use SimpleIT\ClaireAppBundle\SimpleITClaireAppBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Kernel;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class KernelForTest extends Kernel
{
    public function __construct($debug = false)
    {
        parent::__construct('test', $debug);
    }

    public function getBundleMap()
    {
        return $this->bundleMap;
    }

    public function registerBundles()
    {
        return array(new SimpleITAppBundle(), new SimpleITClaireAppBundle());
    }

    public function init()
    {
    }

    public function registerBundleDirs()
    {
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ .'/config.yml');
        $loader->load(__DIR__ . '/container_stub.xml');

    }

    public function initializeBundles()
    {
        parent::initializeBundles();
    }

    public function isBooted()
    {
        return $this->booted;
    }

    public function setIsBooted($value)
    {
        $this->booted = (Boolean) $value;
    }

    public function shutdown()
    {
        parent::shutdown();
        $fs = new Filesystem();
        $fs->remove(array(__DIR__ . '/cache', __DIR__ . '/logs'));
    }
}
