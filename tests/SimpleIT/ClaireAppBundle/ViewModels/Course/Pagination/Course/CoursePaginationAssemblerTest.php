<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\Course;

use OC\CLAIRE\BusinessRules\Entities\Course\Course\DisplayLevel;
use OC\CLAIRE\BusinessRules\Entities\Course\Course\Status;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\Pagination;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\PaginationAssemblerTest;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\PaginationTocStub1;

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
        $this->createFromToc(self::INVALID_DISPLAY_LEVEL, Status::DRAFT);
    }

    /**
     * @test
     * @expectedException \SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\InvalidStatusException
     */
    public function InvalidStatus_ThrowException()
    {
        $this->createFromToc(DisplayLevel::MEDIUM, self::INVALID_STATUS);
    }

    private function createFromToc($displayLevel, $status)
    {
        $this->pagination = $this->paginationAssembler->createFromToc(
            new PaginationTocStub1(),
            $displayLevel,
            self::COURSE_1_CATEGORY_SLUG,
            $status
        );
    }

    /**
     * @test
     */
    public function SmallCourse_NoPagination()
    {
        $this->createFromToc(DisplayLevel::SMALL, Status::PUBLISHED);
        $this->assertEquals(new Pagination(), $this->pagination);
    }

    /**
     * @test
     */
    public function DraftMediumCourse_PreviousNullNextFirstTitle1()
    {
        $this->createFromToc(DisplayLevel::MEDIUM, Status::DRAFT);

        $this->assertPagination(new DraftMediumCoursePaginationExpected());

    }

    /**
     * @test
     */
    public function PublishedMediumCourse_PreviousNullNextFirstTitle1()
    {
        $this->createFromToc(DisplayLevel::MEDIUM, Status::PUBLISHED);
        $this->assertPagination(new PublishedMediumCoursePaginationExpected());
    }

    /**
     * @test
     */
    public function PublishedBigCourse_First()
    {
        $this->createFromToc(DisplayLevel::BIG, Status::PUBLISHED);
        $this->assertPagination(new PublishedBigCoursePaginationExpected());

    }

    protected function setUp()
    {
        $this->paginationAssembler = new CoursePaginationAssembler();
        parent::setUp();
    }
}
