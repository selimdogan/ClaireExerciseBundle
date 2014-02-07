<?php

namespace OC\CLAIRE\BusinessRules\UseCases\AssociatedContent\CategoryByCourse;

use OC\CLAIRE\BusinessRules\Gateways\AssociatedContent\Category\CategoryByCourseGatewaySpy;
use
    OC\CLAIRE\BusinessRules\Gateways\AssociatedContent\Category\CourseNotFoundCategoryByCourseGatewayStub;
use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseNotFoundCourseGatewayStub;
use OC\CLAIRE\BusinessRules\Gateways\Course\Course\DraftAndPublishedCourseGatewayStub;
use OC\CLAIRE\BusinessRules\Gateways\Course\Course\OnlyDraftCourseGatewayStub;
use
    OC\CLAIRE\BusinessRules\UseCases\AssociatedContent\CategoryByCourse\DTO\SaveCourseCategoryRequestDTO;
use OC\CLAIRE\BusinessRules\UseCases\Course\Course\GetCourseStatuses;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class SaveCourseCategoryTest extends \PHPUnit_Framework_TestCase
{
    const NON_EXISTING_COURSE_ID = 999;

    const COURSE_ID = 1;

    const CATEGORY_ID = 1;

    /**
     * @var SaveCourseCategory
     */
    private $useCase;

    /**
     * @test
     * @expectedException \OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException
     */
    public function NonExistingCourse_ThrowException()
    {
        $ucGetCoursesStatuses = new GetCourseStatuses();
        $ucGetCoursesStatuses->setCourseGateway(new CourseNotFoundCourseGatewayStub());
        $this->useCase->setGetCourseStatuses($ucGetCoursesStatuses);
        $this->useCase->setCategoryByCourseGateway(new CourseNotFoundCategoryByCourseGatewayStub());
        $this->useCase->execute(
            new SaveCourseCategoryRequestDTO(self::NON_EXISTING_COURSE_ID, self::CATEGORY_ID)
        );
    }

    /**
     * @test
     * @expectedException \OC\CLAIRE\BusinessRules\Exceptions\AssociatedContent\Category\SaveCategoryByCourseException
     */
    public function PublishedCourse_ThrowException()
    {
        $ucGetCoursesStatuses = new GetCourseStatuses();
        $ucGetCoursesStatuses->setCourseGateway(new DraftAndPublishedCourseGatewayStub());
        $this->useCase->setGetCourseStatuses($ucGetCoursesStatuses);
        $this->useCase->setCategoryByCourseGateway(new CategoryByCourseGatewaySpy());
        $this->useCase->execute(
            new SaveCourseCategoryRequestDTO(self::COURSE_ID, self::CATEGORY_ID)
        );
    }

    /**
     * @test
     */
    public function SaveCourseCategory()
    {
        $gateway = new CategoryByCourseGatewaySpy();
        $ucGetCoursesStatuses = new GetCourseStatuses();
        $ucGetCoursesStatuses->setCourseGateway(new OnlyDraftCourseGatewayStub());
        $this->useCase->setGetCourseStatuses($ucGetCoursesStatuses);
        $this->useCase->setCategoryByCourseGateway($gateway);
        $this->useCase->execute(
            new SaveCourseCategoryRequestDTO(self::COURSE_ID, self::CATEGORY_ID)
        );

        $this->assertEquals(self::COURSE_ID, $gateway->courseId);
        $this->assertEquals(self::CATEGORY_ID, $gateway->categoryId);
    }

    protected function setup()
    {
        $this->useCase = new SaveCourseCategory();
    }
}
