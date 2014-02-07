<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\Part;

use OC\CLAIRE\BusinessRules\Entities\Course\Course\DisplayLevel;
use OC\CLAIRE\BusinessRules\Entities\Course\Course\Status;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\PaginationAssemblerTest;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\PaginationTitleOneStub1;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\PaginationTitleOneStub2;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\PaginationTitleOneStub3;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\PaginationTitleThreeStub1;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\PaginationTitleThreeStub2;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\PaginationTitleThreeStub3;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\PaginationTitleTwoStub1;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\PaginationTitleTwoStub2;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\PaginationTitleTwoStub3;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\PaginationTocStub1;

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
        $this->create(self::INVALID_DISPLAY_LEVEL, Status::PUBLISHED, 1);
    }

    private function create($displayLevel, $status, $partIdentifier)
    {
        $this->pagination = $this->paginationAssembler->createFromToc(
            new PaginationTocStub1(),
            $displayLevel,
            self::COURSE_1_CATEGORY_SLUG,
            $status,
            $partIdentifier
        );
    }

    /**
     * @test
     * @expectedException \SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\InvalidStatusException
     */
    public function InvalidStatus_ThrowException()
    {
        $this->create(DisplayLevel::MEDIUM, self::INVALID_STATUS, 1);
    }

    /**
     * @test
     * @expectedException \SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\InvalidDisplayLevelException
     */
    public function SmallCourse_ThrowException()
    {
        $this->create(DisplayLevel::SMALL, Status::PUBLISHED, 1);
    }

    /**
     * @test
     */
    public function PublishedMediumCourseFirstTitle1_ReturnPreviousCourseNextTitle1()
    {
        $this->create(DisplayLevel::MEDIUM, Status::PUBLISHED, PaginationTitleOneStub1::SLUG);
        $this->assertPagination(new PublishedMediumCourseFirstTitle1PaginationExpected());
    }

    /**
     * @test
     */
    public function PublishedMediumCourseSecondTitle1_ReturnFirstTitle1NextTitle1()
    {
        $this->create(DisplayLevel::MEDIUM, Status::PUBLISHED, PaginationTitleOneStub2::SLUG);
        $this->assertPagination(new PublishedMediumCourseSecondTitle1PaginationExpected());
    }

    /**
     * @test
     */
    public function PublishedMediumCourseLastTitle1_ReturnPreviousTitle1()
    {
        $this->create(DisplayLevel::MEDIUM, Status::PUBLISHED, PaginationTitleOneStub3::SLUG);
        $this->assertPagination(new PublishedMediumCourseLastTitle1PaginationExpected());
    }

    /**
     * @test
     */
    public function DraftMediumCourseFirstTitle1_ReturnPreviousCourseNextTitle1()
    {
        $this->create(DisplayLevel::MEDIUM, Status::DRAFT, PaginationTitleOneStub1::SLUG);
        $this->assertPagination(new DraftMediumCourseFirstTitle1PaginationExpected());
    }

    /**
     * @test
     */
    public function DraftMediumCourseSecondTitle1_ReturnFirstTitle1NextTitle1()
    {
        $this->create(DisplayLevel::MEDIUM, Status::DRAFT, PaginationTitleOneStub2::SLUG);
        $this->assertPagination(new DraftMediumCourseSecondTitle1PaginationExpected());
    }

    /**
     * @test
     */
    public function DraftMediumCourseLastTitle1_ReturnPreviousTitle1()
    {
        $this->create(DisplayLevel::MEDIUM, Status::DRAFT, PaginationTitleOneStub3::SLUG);
        $this->assertPagination(new DraftMediumCourseLastTitle1PaginationExpected());
    }

    /**
     * @test
     */
    public function PublishedBigCourseFirstTitle2_ReturnPreviousCourseFirstTitle3()
    {
        $this->create(DisplayLevel::BIG, Status::PUBLISHED, PaginationTitleTwoStub1::SLUG);
        $this->assertPagination(new PublishedBigCourseFirstTitle2PaginationExpected());
    }

    /**
     * @test
     */
    public function PublishedBigCourseFirstTitle3_ReturnPreviousFirstTitle2NextTitle3()
    {
        $this->create(DisplayLevel::BIG, Status::PUBLISHED, PaginationTitleThreeStub1::SLUG);
        $this->assertPagination(new PublishedBigCourseFirstTitle3PaginationExpected());
    }

    /**
     * @test
     */
    public function PublishedBigCourseSecondTitle3_ReturnPreviousFirstTitle3NextTitle2()
    {
        $this->create(DisplayLevel::BIG, Status::PUBLISHED, PaginationTitleThreeStub2::SLUG);
        $this->assertPagination(new PublishedBigCourseLastTitle2Title3PaginationExpected());
    }

    /**
     * @test
     */
    public function PublishedBigCourseSecondTitle2_ReturnPreviousTitle3NextTitle2()
    {
        $this->create(DisplayLevel::BIG, Status::PUBLISHED, PaginationTitleTwoStub2::SLUG);
        $this->assertPagination(new PublishedBigCourseSecondTitle2PaginationExpected());
    }

    /**
     * @test
     */
    public function PublishedBigCourseLastPartTitle2_ReturnPreviousTitle3NextPartFirstTitle2()
    {
        $this->create(DisplayLevel::BIG, Status::PUBLISHED, PaginationTitleTwoStub3::SLUG);
        $this->assertPagination(new PublishedBigCourseLastPartTitle2PaginationExpected());
    }

    /**
     * @test
     */
    public function PublishedBigCourseLastTitle3_ReturnPreviousTitle3NextNull()
    {
        $this->create(DisplayLevel::BIG, Status::PUBLISHED, PaginationTitleThreeStub3::SLUG);
        $this->assertPagination(new PublishedBigCourseLastTitle3PaginationExpected());
    }

    /**
     * @test
     */
    public function DraftBigCourseFirstTitle2_ReturnPreviousCourseNextTitle3()
    {
        $this->create(DisplayLevel::BIG, Status::DRAFT, PaginationTitleTwoStub1::SLUG);
        $this->assertPagination(new DraftBigCourseFirstTitle2PaginationExpected());
    }

//    /**
//     * @test
//     */
//    public function DraftBigCourseSecondTitle2_ReturnPreviousTitle2NextTitle2()
//    {
//        $this->create(DisplayLevel::BIG, Status::DRAFT, PaginationTitleTwoStub2::SLUG);
//        $this->assertPagination(new DraftBigCourseSecondTitle2PaginationExpected());
//    }
//
//    /**
//     * @test
//     */
//    public function DraftBigCourseLastPartTitle2_ReturnPreviousTitle2NextPartFirstTitle2()
//    {
//        $this->create(DisplayLevel::BIG, Status::DRAFT, PaginationTitleTwoStub3::SLUG);
//        $this->assertPagination(new DraftBigCourseLastPartTitle2PaginationExpected());
//    }
//
//    /**
//     * @test
//     */
//    public function DraftBigCourseLastTitle2_ReturnPreviousTitle2NextNull()
//    {
//        $this->create(DisplayLevel::BIG, Status::DRAFT, PaginationTitleTwoStub4::SLUG);
//        $this->assertPagination(new DraftBigCourseLastTitle2PaginationExpected());
//    }

    protected function setUp()
    {
        $this->paginationAssembler = new PartPaginationAssembler();
        parent::setUp();
    }
}
