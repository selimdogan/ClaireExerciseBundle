<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\Part;

use OC\CLAIRE\BusinessRules\Entities\Course\Course\DisplayLevel;
use OC\CLAIRE\BusinessRules\Entities\Course\Course\Status;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\Pagination;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\PaginationAssemblerTest;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\PaginationTocStub1;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\TitleOneStub;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\TitleOneStub2;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\TitleOneStub3;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\TitleTwoStub;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\TitleTwoStub2;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\TitleTwoStub3;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\TocStub1;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class PartPaginationAssemblerTest extends PaginationAssemblerTest
{
    const COURSE_1_CATEGORY_SLUG = 'category-slug';

    const COURSE_1_ID = 1;

    const COURSE_1_SLUG = 'course-1-slug';

    const PART_1_ID = 1;

    const PART_1_SLUG = 'course-1-slug';

    const ROUTING_FILE = 'route.yml';

    /**
     * @var PartPaginationAssembler
     */
    protected $paginationAssembler;

    /**
     * @test
     * @expectedException \SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\InvalidDisplayLevelException
     */
    public function InvalidDisplayLevel_ThrowException()
    {
        $this->paginationAssembler->createFromToc(
            new PaginationTocStub1(),
            3,
            self::COURSE_1_ID,
            self::COURSE_1_CATEGORY_SLUG,
            Status::PUBLISHED,
            self::PART_1_ID
        );
    }

    /**
     * @test
     * @expectedException \SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\InvalidDisplayLevelException
     */
    public function SmallCourse_ThrowException()
    {
        $this->paginationAssembler->createFromToc(
            new PaginationTocStub1(),
            DisplayLevel::SMALL,
            self::COURSE_1_ID,
            self::COURSE_1_CATEGORY_SLUG,
            Status::PUBLISHED,
            self::PART_1_ID
        );
    }

    /**
     * @test
     */
    public function MediumCourseFirstTitle1_ReturnPreviousCourseNextTitle1()
    {
        $pagination = $this->paginationAssembler->createFromToc(
            new PaginationTocStub1(),
            DisplayLevel::MEDIUM,
            self::COURSE_1_ID,
            self::COURSE_1_CATEGORY_SLUG,
            Status::PUBLISHED,
            TitleOneStub::ID
        );

        $this->assertEquals(TocStub1::TITLE, $pagination->previousTitle);
        $this->assertEquals('/category-slug/cours/1', $pagination->previousUrl);
        $this->assertEquals(TitleOneStub2::TITLE, $pagination->nextTitle);
        $this->assertEquals(
            '/category-slug/cours/' . self::COURSE_1_ID . '/' . TitleOneStub2::SLUG,
            $pagination->nextUrl
        );
    }

    /**
     * @test
     */
    public function MediumCourseSecondTitle1_ReturnFirstTitle1NextTitle1()
    {
        /** @var Pagination $pagination */
        $pagination = $this->paginationAssembler->createFromToc(
            new PaginationTocStub1(),
            DisplayLevel::MEDIUM,
            self::COURSE_1_ID,
            self::COURSE_1_CATEGORY_SLUG,
            Status::PUBLISHED,
            TitleOneStub2::ID
        );

        $this->assertEquals(TitleOneStub::TITLE, $pagination->previousTitle);
        $this->assertEquals(
            '/category-slug/cours/' . self::COURSE_1_ID . '/' . TitleOneStub::SLUG,
            $pagination->previousUrl
        );
        $this->assertEquals(TitleOneStub3::TITLE, $pagination->nextTitle);
        $this->assertEquals(
            '/category-slug/cours/' . self::COURSE_1_ID . '/' . TitleOneStub3::SLUG,
            $pagination->nextUrl
        );
    }

    /**
     * @test
     */
    public function MediumCourseLastTitle1_ReturnPreviousTitle1()
    {
        $pagination = $this->paginationAssembler->createFromToc(
            new PaginationTocStub1(),
            DisplayLevel::MEDIUM,
            self::COURSE_1_ID,
            self::COURSE_1_CATEGORY_SLUG,
            Status::PUBLISHED,
            TitleOneStub3::ID
        );

        $this->assertEquals(TitleOneStub2::TITLE, $pagination->previousTitle);
        $this->assertEquals(
            '/category-slug/cours/' . self::COURSE_1_ID . '/' . TitleOneStub2::SLUG,
            $pagination->previousUrl
        );
        $this->assertNull($pagination->nextTitle);
        $this->assertNull($pagination->nextUrl);
    }

    /**
     * @test
     */
    public function BigCourseFirstTitle2_ReturnPreviousCourseNextTitle2()
    {
        $pagination = $this->paginationAssembler->createFromToc(
            new PaginationTocStub1(),
            DisplayLevel::BIG,
            self::COURSE_1_ID,
            self::COURSE_1_CATEGORY_SLUG,
            Status::PUBLISHED,
            TitleTwoStub::ID
        );

        $this->assertEquals(TocStub1::TITLE, $pagination->previousTitle);
        $this->assertEquals('/category-slug/cours/1', $pagination->previousUrl);
        $this->assertEquals(TitleTwoStub2::TITLE, $pagination->nextTitle);
        $this->assertEquals(
            '/category-slug/cours/' . self::COURSE_1_ID . '/' . TitleTwoStub2::SLUG,
            $pagination->nextUrl
        );
    }

    /**
     * @test
     */
    public function BigCourseSecondTitle2_ReturnPreviousTitle2NextTitle2()
    {
        $pagination = $this->paginationAssembler->createFromToc(
            new PaginationTocStub1(),
            DisplayLevel::BIG,
            self::COURSE_1_ID,
            self::COURSE_1_CATEGORY_SLUG,
            Status::PUBLISHED,
            TitleTwoStub2::ID
        );

        $this->assertEquals(TitleTwoStub::TITLE, $pagination->previousTitle);
        $this->assertEquals(
            '/category-slug/cours/' . self::COURSE_1_ID . '/' . TitleTwoStub::SLUG,
            $pagination->previousUrl
        );
        $this->assertEquals(TitleTwoStub3::TITLE, $pagination->nextTitle);
        $this->assertEquals(
            '/category-slug/cours/' . self::COURSE_1_ID . '/' . TitleTwoStub3::SLUG,
            $pagination->nextUrl
        );
    }

    /**
     * @test
     */
    public function BigCourseLastPartTitle2_ReturnPreviousTitle2NextPartFirstTitle2()
    {
        $pagination = $this->paginationAssembler->createFromToc(
            new PaginationTocStub1(),
            DisplayLevel::BIG,
            self::COURSE_1_ID,
            self::COURSE_1_CATEGORY_SLUG,
            Status::PUBLISHED,
            TitleTwoStub3::ID
        );

        $this->assertEquals(TitleTwoStub2::TITLE, $pagination->previousTitle);
        $this->assertEquals(
            '/category-slug/cours/' . self::COURSE_1_ID . '/' . TitleTwoStub2::SLUG,
            $pagination->previousUrl
        );
        $this->assertEquals(TitleTwoStub::TITLE, $pagination->nextTitle);
        $this->assertEquals(
            '/category-slug/cours/' . self::COURSE_1_ID . '/' . TitleTwoStub::SLUG,
            $pagination->nextUrl
        );
    }

    protected function setUp()
    {
        $this->paginationAssembler = new PartPaginationAssembler();
        parent::setUp();
    }
}
