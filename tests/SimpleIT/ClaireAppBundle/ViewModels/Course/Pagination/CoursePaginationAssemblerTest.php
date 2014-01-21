<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination;

use OC\CLAIRE\BusinessRules\Entities\Course\Course\DisplayLevel;
use OC\CLAIRE\BusinessRules\Entities\Course\Course\Status;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\TitleOneStub;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\TocStub1;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CoursePaginationAssemblerTest extends \PHPUnit_Framework_TestCase
{
    const COURSE_1_CATEGORY_SLUG = 'category-slug';

    const COURSE_1_ID = 1;

    const COURSE_1_SLUG = 'course-1-slug';

    const ROUTING_FILE = 'route.yml';

    /**
     * @var CoursePaginationAssembler
     */
    private $paginationAssembler;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @test
     */
    public function SmallCourse_NoPagination()
    {
        $paginationAssembler = new CoursePaginationAssembler();
        $pagination = $paginationAssembler->createFromToc(
            new TitleOneStub(),
            DisplayLevel::SMALL,
            self::COURSE_1_SLUG,
            self::COURSE_1_CATEGORY_SLUG,
            Status::PUBLISHED
        );
        $this->assertEquals(new Pagination(), $pagination);
    }

    /**
     * @test
     */
    public function DraftMediumCourse_First()
    {
        $pagination = $this->paginationAssembler->createFromToc(
            new TocStub1(),
            DisplayLevel::MEDIUM,
            self::COURSE_1_ID,
            self::COURSE_1_CATEGORY_SLUG,
            Status::DRAFT
        );

        $this->assertNull($pagination->previousTitle);
        $this->assertNull($pagination->previousUrl);
        $this->assertEquals(DraftMediumCoursePaginationStub::NEXT_TITLE, $pagination->nextTitle);
        $this->assertEquals(DraftMediumCoursePaginationStub::NEXT_URL, $pagination->nextUrl);
    }

    /**
     * @test
     */
    public function PublishedMediumCourse_First()
    {
        $pagination = $this->paginationAssembler->createFromToc(
            new TocStub1(),
            DisplayLevel::MEDIUM,
            self::COURSE_1_SLUG,
            self::COURSE_1_CATEGORY_SLUG,
            Status::PUBLISHED
        );

        $this->assertNull($pagination->previousTitle);
        $this->assertNull($pagination->previousUrl);
        $this->assertEquals(PublishedMediumCoursePaginationStub::NEXT_TITLE, $pagination->nextTitle);
        $this->assertEquals(PublishedMediumCoursePaginationStub::NEXT_URL, $pagination->nextUrl);
    }

    /**
     * @test
     */
    public function PublishedBigCourse_First()
    {
        $pagination = $this->paginationAssembler->createFromToc(
            new TocStub1(),
            DisplayLevel::BIG,
            self::COURSE_1_SLUG,
            self::COURSE_1_CATEGORY_SLUG,
            Status::PUBLISHED
        );

        $this->assertNull($pagination->previousTitle);
        $this->assertNull($pagination->previousUrl);
        $this->assertEquals(PublishedBigCoursePaginationStub::NEXT_TITLE, $pagination->nextTitle);
        $this->assertEquals(PublishedBigCoursePaginationStub::NEXT_URL, $pagination->nextUrl);
    }

    protected function setUp()
    {
        $this->router = new Router(
            new YamlFileLoader(new FileLocator(array(__DIR__))),
            self::ROUTING_FILE
        );

        $this->paginationAssembler = new CoursePaginationAssembler();
        $this->paginationAssembler->setRouter($this->router);

    }

}
