<?php

namespace SimpleIT\ClaireAppBundle\UseCases;

use SimpleIT\ClaireAppBundle\Requestors\UseCaseFactory;
use SimpleIT\ClaireAppBundle\Util\KernelForTest;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Kernel;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */

/** @noinspection PhpUndefinedNamespaceInspection */
class UseCaseFactoryTest extends \PHPUnit_Framework_TestCase
{
    const INVALID_USE_CASE_NAME = 'Invalid use case name';

    protected $CourseUseCases = array(
        'GetPublishedCourse'                  => 'SimpleIT\ClaireAppBundle\UseCases\Course\Course\GetPublishedCourse',
        'GetWaitingForPublicationCourse'      => 'SimpleIT\ClaireAppBundle\UseCases\Course\Course\GetWaitingForPublicationCourse',
        'GetDraftCourse'                      => 'SimpleIT\ClaireAppBundle\UseCases\Course\Course\GetDraftCourse',
        'GetPublishedContent'                 => 'SimpleIT\ClaireAppBundle\UseCases\Course\Content\GetPublishedContent',
        'GetWaitingForPublicationContent'     => 'SimpleIT\ClaireAppBundle\UseCases\Course\Content\GetWaitingForPublicationContent',
        'GetDraftContent'                     => 'SimpleIT\ClaireAppBundle\UseCases\Course\Content\GetDraftContent',
        'SaveContent'                         => 'SimpleIT\ClaireAppBundle\UseCases\Course\Content\SaveContent',
        'ChangeCourseToWaitingForPublication' => 'SimpleIT\ClaireAppBundle\UseCases\Course\Workflow\ChangeCourseToWaitingForPublication',
        'ChangeCourseToPublished'             => 'SimpleIT\ClaireAppBundle\UseCases\Course\Workflow\ChangeCourseToPublished',
        'AddElementToToc'                     => 'SimpleIT\ClaireAppBundle\UseCases\Course\Toc\AddElementToToc'
    );

    /**
     * @var Kernel
     */
    private $kernel;

    /**
     * @var UseCaseFactory
     */
    private $useCaseFactory;

    /**
     * @test
     * @expectedException SimpleIT\ClaireAppBundle\Requestors\InvalidUseCaseException
     */
    public function InvalidUseCaseName_ThrowException()
    {
        $this->useCaseFactory->make(self::INVALID_USE_CASE_NAME);
    }

    /**
     * @test
     */
    public function Make_ReturnCourseUseCaseClass()
    {
        foreach ($this->CourseUseCases as $useCaseName => $useCaseClass) {
            $this->assertUseCase($useCaseName, $useCaseClass);
        }

    }

    protected function assertUseCase($useCaseName, $useCaseClass)
    {
        $useCase = $this->useCaseFactory->make($useCaseName);
        $this->assertEquals($useCaseClass, get_class($useCase));
    }

    protected function setup()
    {
        $fs = new Filesystem();
        $fs->remove(array(__DIR__ . '/../Util/cache', __DIR__ . '/../Util/logs'));

        $this->kernel = new KernelForTest();
        $this->kernel->boot();
        $container = $this->kernel->getContainer();
        $this->useCaseFactory = new UseCaseFactoryImpl();
        $this->useCaseFactory->setInjector($container);
    }

    protected function tearDown()
    {
        $this->kernel->shutdown();
    }
}
