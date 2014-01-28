<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Part\DTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class SavePartBuilderImplTest extends \PHPUnit_Framework_TestCase
{
    const COURSE_ID = 1;

    const PART_ID = 10;

    /**
     * @test
     * @expectedException \OC\CLAIRE\BusinessRules\Requestors\Course\Part\SavePartRequestException
     * @expectedExceptionMessage Part id is required
     */
    public function BuildWithoutPartId_ThrowException()
    {
        SavePartRequestBuilder::create()->fromCourse(self::COURSE_ID)->build();
    }

    /**
     * @test
     * @expectedException \OC\CLAIRE\BusinessRules\Requestors\Course\Part\SavePartRequestException
     * @expectedExceptionMessage Course id is required
     */
    public function BuildWithoutCourseId_ThrowException()
    {
        SavePartRequestBuilder::create()->part(self::PART_ID)->build();
    }

    /**
     * @test
     * @expectedException \OC\CLAIRE\BusinessRules\Requestors\Course\Part\SavePartRequestException
     * @expectedExceptionMessage Course id and part id are required
     */
    public function BuildWithoutCourseIdAndPartId_ThrowException()
    {
        SavePartRequestBuilder::create()->build();
    }

}
