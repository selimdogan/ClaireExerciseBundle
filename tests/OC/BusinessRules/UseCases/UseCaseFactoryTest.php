<?php

namespace OC\BusinessRules\UseCases;

use OC\BusinessRules\Requestors\UseCaseFactory;
use OC\BusinessRules\Util\KernelForTest;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Kernel;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class UseCaseFactoryTest extends \PHPUnit_Framework_TestCase
{
    const INVALID_USE_CASE_NAME = 'Invalid use case name';

    protected $CourseUseCases = array(
        'GetPublishedCourse'                  => 'OC\BusinessRules\UseCases\Course\Course\GetPublishedCourse',
        'GetWaitingForPublicationCourse'      => 'OC\BusinessRules\UseCases\Course\Course\GetWaitingForPublicationCourse',
        'GetDraftCourse'                      => 'OC\BusinessRules\UseCases\Course\Course\GetDraftCourse',
        'GetPublishedContent'                 => 'OC\BusinessRules\UseCases\Course\Content\GetPublishedContent',
        'GetWaitingForPublicationContent'     => 'OC\BusinessRules\UseCases\Course\Content\GetWaitingForPublicationContent',
        'GetDraftContent'                     => 'OC\BusinessRules\UseCases\Course\Content\GetDraftContent',
        'SaveContent'                         => 'OC\BusinessRules\UseCases\Course\Content\SaveContent',
        'ChangeCourseToWaitingForPublication' => 'OC\BusinessRules\UseCases\Course\Workflow\ChangeCourseToWaitingForPublication',
        'ChangeCourseToPublished'             => 'OC\BusinessRules\UseCases\Course\Workflow\ChangeCourseToPublished',
        'AddElementToToc'                     => 'OC\BusinessRules\UseCases\Course\Toc\AddElementToToc'
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
     * @expectedException \OC\BusinessRules\Requestors\InvalidUseCaseException
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
