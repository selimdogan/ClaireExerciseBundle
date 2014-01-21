<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\Course;

use OC\CLAIRE\BusinessRules\Entities\Course\Course\DisplayLevel;
use OC\CLAIRE\BusinessRules\Entities\Course\Course\Status;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\Pagination;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\PaginationAssemblerTest;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\TitleOneStub;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\TocStub1;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CoursePaginationAssemblerTest extends PaginationAssemblerTest
{
    /**
     * @var CoursePaginationAssembler
     */
    protected $paginationAssembler;

    /**
     * @test
     * @expectedException \SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\InvalidDisplayLevelException
     */
    public function InvalidDisplayLevel_ThrowException()
    {
        $this->paginationAssembler->createFromToc(
            new TitleOneStub(),
            3,
            self::COURSE_1_SLUG,
            self::COURSE_1_CATEGORY_SLUG,
            Status::PUBLISHED
        );
    }

    /**
     * @test
     */
    public function SmallCourse_NoPagination()
    {
        $pagination = $this->paginationAssembler->createFromToc(
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
        $this->assertEquals(
            PublishedMediumCoursePaginationStub::NEXT_TITLE,
            $pagination->nextTitle
        );
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
        $this->paginationAssembler = new CoursePaginationAssembler();
        parent::setUp();
    }
}
