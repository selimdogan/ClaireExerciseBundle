<?php

namespace SimpleIT\ClaireAppBundle\UseCaseFactory;

use OC\CLAIRE\BusinessRules\Requestors\UseCaseFactory;
use OC\CLAIRE\BusinessRules\Util\KernelForTest;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Kernel;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
abstract class UseCaseFactoryTest extends \PHPUnit_Framework_TestCase
{
    const INVALID_USE_CASE_NAME = 'Invalid use case name';

    /**
     * @var UseCaseFactory
     */
    protected $useCaseFactory;

    /**
     * @var array
     */
    protected $useCases;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var Kernel
     */
    private $kernel;

    /**
     * @test
     * @expectedException \OC\CLAIRE\BusinessRules\Requestors\InvalidUseCaseException
     */
    public function InvalidUseCaseName_ThrowException()
    {
        $this->useCaseFactory->make(self::INVALID_USE_CASE_NAME);
    }

    /**
     * @test
     */
    public function Make_ReturnUseCaseClass()
    {
        $this->useCaseFactory->setInjector($this->container);
        foreach ($this->useCases as $useCaseName => $useCaseClass) {
            $useCase = $this->useCaseFactory->make($useCaseName);
            $this->assertEquals($useCaseClass, get_class($useCase));
        }
    }

    protected function setup()
    {
        $fs = new Filesystem();
        $fs->remove(array(__DIR__ . '/../Util/cache', __DIR__ . '/../Util/logs'));

        $this->kernel = new KernelForTest();
        $this->kernel->boot();
        $this->container = $this->kernel->getContainer();
    }

    protected function tearDown()
    {
        $this->kernel->shutdown();
    }
}
