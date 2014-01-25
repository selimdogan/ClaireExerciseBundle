<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\CourseDescription;

use OC\CLAIRE\BusinessRules\Entities\Course\Course\SmallEasyDraftCourseStub;
use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseGatewayStub;
use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseNotFoundCourseGatewayStub;
use OC\CLAIRE\BusinessRules\Responders\Course\CourseDescription\GetDraftCourseDescriptionResponse;
use
    OC\CLAIRE\BusinessRules\UseCases\Course\CourseDescription\DTO\GetDraftCourseDescriptionRequestDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftCourseDescriptionTest extends \PHPUnit_Framework_TestCase
{
    const COURSE_ID = 1;

    const NON_EXISTING_COURSE_ID = 999;

    /**
     * @var GetDraftCourseDescription
     */
    private $useCase;

    /**
     * @test
     * @expectedException \OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException
     */
    public function NonExistingCourse_ThrowException()
    {
        $this->useCase->setCourseGateway(new CourseNotFoundCourseGatewayStub());
        $this->useCase->execute(
            new GetDraftCourseDescriptionRequestDTO(self::NON_EXISTING_COURSE_ID)
        );
    }

    /**
     * @test
     */
    public function ReturnDescription()
    {
        $this->useCase->setCourseGateway(new CourseGatewayStub());
        /** @var GetDraftCourseDescriptionResponse $response */
        $response = $this->useCase->execute(
            new GetDraftCourseDescriptionRequestDTO(self::COURSE_ID)
        );

        $this->assertEquals(
            SmallEasyDraftCourseStub::COURSE_DESCRIPTION,
            $response->getCourseDescription()
        );
    }

    protected function setup()
    {
        $this->useCase = new GetDraftCourseDescription();
    }
}
