<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Course\DTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class SaveCourseBuilderImplTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException \OC\CLAIRE\BusinessRules\Requestors\Course\Course\SaveCourseRequestException
     * @expectedExceptionMessage Course id is required
     */
    public function BuildWithoutCourseId_ThrowException()
    {
        SaveCourseRequestBuilder::create()->build();
    }
}
