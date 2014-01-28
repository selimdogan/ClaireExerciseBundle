<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Course;

use OC\CLAIRE\BusinessRules\Entities\Course\Course\Difficulty;
use OC\CLAIRE\BusinessRules\Entities\Course\Course\DisplayLevel;
use OC\CLAIRE\BusinessRules\Entities\Course\Course\Duration;
use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseGatewaySpy;
use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseNotFoundCourseGatewayStub;
use OC\CLAIRE\BusinessRules\UseCases\Course\Course\DTO\SaveCourseRequestBuilder;
use SimpleIT\ApiResourcesBundle\Course\CourseResource;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class SaveCourseTest extends \PHPUnit_Framework_TestCase
{
    const NON_EXISTING_COURSE_ID = 9;

    const COURSE_1_ID = 1;

    const COURSE_1_IMAGE = 'http://example.com/course-1-image.png';

    const COURSE_1_DESCRIPTION = 'Course 1 description';

    const COURSE_1_DIFFICULTY = Difficulty::EASY;

    const COURSE_1_DISPLAY_LEVEL = DisplayLevel::BIG;

    const COURSE_1_DURATION = Duration::P1D;

    const COURSE_1_TITLE = 'Course 1 title';

    /**
     * @var GetCourse
     */
    protected $useCase;

    /**
     * @var CourseGatewaySpy
     */
    protected $courseGateway;

    /**
     * @test
     * @expectedException \OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException
     */
    public function NonExistingCourse_ThrowException()
    {
        $this->useCase->setCourseGateway(new CourseNotFoundCourseGatewayStub());
        $this->useCase->execute(
            SaveCourseRequestBuilder::create()
                ->course(self::NON_EXISTING_COURSE_ID)
                ->build()
        );
    }

    /**
     * @test
     */
    public function saveCourseDescription()
    {
        $this->useCase->execute(
            SaveCourseRequestBuilder::create()
                ->course(self::COURSE_1_ID)
                ->withDescription(self::COURSE_1_DESCRIPTION)
                ->build()
        );
        $expectedCourse = new CourseResource();
        $expectedCourse->setDescription(self::COURSE_1_DESCRIPTION);
        $this->assertCourse($expectedCourse);
    }

    private function assertCourse($expectedResource)
    {
        $this->assertEquals(self::COURSE_1_ID, $this->courseGateway->courseId);
        $this->assertEquals($expectedResource, $this->courseGateway->course);
    }

    /**
     * @test
     */
    public function saveCourseDifficulty()
    {
        $this->useCase->execute(
            SaveCourseRequestBuilder::create()
                ->course(self::COURSE_1_ID)
                ->withDifficulty(self::COURSE_1_DIFFICULTY)
                ->build()
        );
        $expectedCourse = new CourseResource();
        $expectedCourse->setDifficulty(self::COURSE_1_DIFFICULTY);
        $this->assertCourse($expectedCourse);
    }

    /**
     * @test
     */
    public function saveCourseDisplayLevel()
    {
        $this->useCase->execute(
            SaveCourseRequestBuilder::create()
                ->course(self::COURSE_1_ID)
                ->withDisplayLevel(self::COURSE_1_DISPLAY_LEVEL)
                ->build()
        );
        $expectedCourse = new CourseResource();
        $expectedCourse->setDisplayLevel(self::COURSE_1_DISPLAY_LEVEL);
        $this->assertCourse($expectedCourse);
    }

    /**
     * @test
     */
    public function saveCourseDuration()
    {
        $this->useCase->execute(
            SaveCourseRequestBuilder::create()
                ->course(self::COURSE_1_ID)
                ->withDuration(self::COURSE_1_DURATION)
                ->build()
        );
        $expectedCourse = new CourseResource();
        $expectedCourse->setDuration(self::COURSE_1_DURATION);
        $this->assertCourse($expectedCourse);
    }

    /**
     * @test
     */
    public function saveCourseImage()
    {
        $this->useCase->execute(
            SaveCourseRequestBuilder::create()
                ->course(self::COURSE_1_ID)
                ->withImage(self::COURSE_1_IMAGE)
                ->build()
        );
        $expectedCourse = new CourseResource();
        $expectedCourse->setImage(self::COURSE_1_IMAGE);
        $this->assertCourse($expectedCourse);
    }

    /**
     * @test
     */
    public function saveCourseTitle()
    {
        $this->useCase->execute(
            SaveCourseRequestBuilder::create()
                ->course(self::COURSE_1_ID)
                ->withTitle(self::COURSE_1_TITLE)
                ->build()
        );
        $expectedCourse = new CourseResource();
        $expectedCourse->setTitle(self::COURSE_1_TITLE);
        $this->assertCourse($expectedCourse);
    }

    protected function setUp()
    {
        $this->useCase = new SaveCourse();
        $this->courseGateway = new CourseGatewaySpy();
        $this->useCase->setCourseGateway($this->courseGateway);
    }
}
