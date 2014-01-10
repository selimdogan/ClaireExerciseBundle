<?php

namespace OC\CLAIRE\BusinessRules\UseCases;

use OC\CLAIRE\BusinessRules\Requestors\UseCaseFactory;
use OC\CLAIRE\BusinessRules\Util\KernelForTest;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Kernel;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class UseCaseFactoryTest extends \PHPUnit_Framework_TestCase
{
    const INVALID_USE_CASE_NAME = 'Invalid use case name';

    protected $CourseUseCases = array(
        'GetPublishedCourse'                  => 'OC\CLAIRE\BusinessRules\UseCases\Course\Course\GetPublishedCourse',
        'GetWaitingForPublicationCourse'      => 'OC\CLAIRE\BusinessRules\UseCases\Course\Course\GetWaitingForPublicationCourse',
        'GetDraftCourse'                      => 'OC\CLAIRE\BusinessRules\UseCases\Course\Course\GetDraftCourse',
        'GetPublishedContent'                 => 'OC\CLAIRE\BusinessRules\UseCases\Course\Content\GetPublishedContent',
        'GetWaitingForPublicationContent'     => 'OC\CLAIRE\BusinessRules\UseCases\Course\Content\GetWaitingForPublicationContent',
        'GetDraftContent'                     => 'OC\CLAIRE\BusinessRules\UseCases\Course\Content\GetDraftContent',
        'SaveContent'                         => 'OC\CLAIRE\BusinessRules\UseCases\Course\Content\SaveContent',
        'ChangeCourseToWaitingForPublication' => 'OC\CLAIRE\BusinessRules\UseCases\Course\Workflow\ChangeCourseToWaitingForPublication',
        'ChangeCourseToPublished'             => 'OC\CLAIRE\BusinessRules\UseCases\Course\Workflow\ChangeCourseToPublished',
        'GetDraftDisplayLevel'                => 'OC\CLAIRE\BusinessRules\UseCases\Course\DisplayLevel\GetDraftDisplayLevel',
        'SaveDisplayLevel'                    => 'OC\CLAIRE\BusinessRules\UseCases\Course\DisplayLevel\SaveDisplayLevel',
        'GetDraftCourseDifficulty'            => 'OC\CLAIRE\BusinessRules\UseCases\Course\CourseDifficulty\GetDraftCourseDifficulty',
        'SaveCourseDifficulty'                => 'OC\CLAIRE\BusinessRules\UseCases\Course\CourseDifficulty\SaveCourseDifficulty',
        'AddElementToToc'                     => 'OC\CLAIRE\BusinessRules\UseCases\Course\Toc\AddElementToToc',
        'GetDraftPartDifficulty'              => 'OC\CLAIRE\BusinessRules\UseCases\Course\PartDifficulty\GetDraftPartDifficulty'
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
     * @expectedException \OC\CLAIRE\BusinessRules\Requestors\InvalidUseCaseException
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
