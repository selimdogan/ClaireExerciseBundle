<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Workflow;

use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseGatewaySpy;
use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseNotFoundCourseGatewayStub;
use
    OC\CLAIRE\BusinessRules\UseCases\Course\Workflow\DTO\DismissWaitingForPublicationCourseRequestDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class DismissWaitingForPublicationCourseTest extends \PHPUnit_Framework_TestCase
{
    const COURSE_1_ID = 1;

    const NON_EXISTING_COURSE_ID = 9;

    /**
     * @var DismissWaitingForPublicationCourse
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
            new DismissWaitingForPublicationCourseRequestDTO(self::NON_EXISTING_COURSE_ID)
        );
    }

    public function Dismiss()
    {
        $gateway = new CourseGatewaySpy();
        $this->useCase->setCourseGateway($gateway);
        $this->useCase->execute(
            new DismissWaitingForPublicationCourseRequestDTO(self::COURSE_1_ID)
        );
        $this->assertEquals(self::COURSE_1_ID, $gateway->courseId);
    }

    protected function setUp()
    {
        $this->useCase = new DismissWaitingForPublicationCourse();
    }

}
