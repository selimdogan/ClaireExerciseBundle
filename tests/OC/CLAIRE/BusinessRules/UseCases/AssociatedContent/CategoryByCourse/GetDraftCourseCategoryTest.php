<?php

namespace OC\CLAIRE\BusinessRules\UseCases\AssociatedContent\CategoryByCourse;

use OC\CLAIRE\BusinessRules\Entities\AssociatedContent\CategoryStub;
use OC\CLAIRE\BusinessRules\Gateways\AssociatedContent\Category\CategoryByCourseGatewaySpy;
use
    OC\CLAIRE\BusinessRules\Gateways\AssociatedContent\Category\CourseNotFoundCategoryByCourseGatewayStub;
use OC\CLAIRE\BusinessRules\Responders\AssociatedContent\Category\GetDraftCourseCategoryResponse;
use
    OC\CLAIRE\BusinessRules\UseCases\AssociatedContent\CategoryByCourse\DTO\GetDraftCourseCategoryRequestDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetDraftCourseCategoryTest extends \PHPUnit_Framework_TestCase
{
    const NON_EXISTING_COURSE_ID = 999;

    const COURSE_ID = 1;

    /**
     * @var GetDraftCourseCategory
     */
    private $useCase;

    /**
     * @test
     * @expectedException \OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException
     */
    public function NonExistingCourse_ThrowException()
    {
        $this->useCase->setCategoryByCourseGateway(new CourseNotFoundCategoryByCourseGatewayStub());
        $this->useCase->execute(new GetDraftCourseCategoryRequestDTO(self::NON_EXISTING_COURSE_ID));
    }

    /**
     * @test
     */
    public function ReturnCategory()
    {
        $this->useCase->setCategoryByCourseGateway(new CategoryByCourseGatewaySpy());
        /** @var GetDraftCourseCategoryResponse $response */
        $response = $this->useCase->execute(new GetDraftCourseCategoryRequestDTO(self::COURSE_ID));
        $this->assertEquals(CategoryStub::ID, $response->getCategoryId());
        $this->assertEquals(CategoryStub::NAME, $response->getCategoryName());

    }

    protected function setup()
    {
        $this->useCase = new GetDraftCourseCategory();
    }
}
