<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\DisplayLevel;

use OC\CLAIRE\BusinessRules\Entities\Course\Course\DisplayLevel;
use OC\CLAIRE\BusinessRules\Gateways\Course\Course\BigHardCourseGatewayStub;
use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseGatewayStub;
use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseNotFoundCourseGatewayStub;
use OC\CLAIRE\BusinessRules\Gateways\Course\Course\MediumMediumCourseGatewayStub;
use OC\CLAIRE\BusinessRules\Responders\Course\DisplayLevel\GetDraftDisplayLevelResponse;
use
    OC\CLAIRE\BusinessRules\UseCases\Course\DisplayLevel\DTO\GetDraftDisplayLevelRequestDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftDisplayLevelTest extends \PHPUnit_Framework_TestCase
{
    const NON_EXISTING_COURSE_ID = 999;

    const COURSE_ID = 1;

    /**
     * @var GetDraftDisplayLevel
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
            new GetDraftDisplayLevelRequestDTO(self::NON_EXISTING_COURSE_ID)
        );
    }

    /**
     * @test
     */
    public function SmallDisplayLevel_ReturnSmallDisplayLevel()
    {
        $this->useCase->setCourseGateway(new CourseGatewayStub());
        /** @var \OC\CLAIRE\BusinessRules\Responders\Course\DisplayLevel\GetDraftDisplayLevelResponse $response */
        $response = $this->useCase->execute(
            new GetDraftDisplayLevelRequestDTO(self::COURSE_ID)
        );
        $this->assertEquals(DisplayLevel::SMALL, $response->getDisplayLevel());
    }

    /**
     * @test
     */
    public function MediumDisplayLevel_ReturnMediumDisplayLevel()
    {
        $this->useCase->setCourseGateway(new MediumMediumCourseGatewayStub());
        /** @var \OC\CLAIRE\BusinessRules\Responders\Course\DisplayLevel\GetDraftDisplayLevelResponse $response */
        $response = $this->useCase->execute(
            new GetDraftDisplayLevelRequestDTO(self::COURSE_ID)
        );
        $this->assertEquals(DisplayLevel::MEDIUM, $response->getDisplayLevel());
    }

    /**
     * @test
     */
    public function BigDisplayLevel_ReturnBigDisplayLevel()
    {
        $this->useCase->setCourseGateway(new BigHardCourseGatewayStub());
        /** @var \OC\CLAIRE\BusinessRules\Responders\Course\DisplayLevel\GetDraftDisplayLevelResponse $response */
        $response = $this->useCase->execute(
            new GetDraftDisplayLevelRequestDTO(self::COURSE_ID)
        );
        $this->assertEquals(DisplayLevel::BIG, $response->getDisplayLevel());
    }

    protected function setup()
    {
        $this->useCase = new GetDraftDisplayLevel();
    }
}
