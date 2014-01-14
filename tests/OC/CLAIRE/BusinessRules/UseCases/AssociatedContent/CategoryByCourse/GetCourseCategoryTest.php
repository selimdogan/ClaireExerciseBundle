<?php

namespace OC\CLAIRE\BusinessRules\UseCases\AssociatedContent\CategoryByCourse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
abstract class GetCourseCategoryTest extends \PHPUnit_Framework_TestCase
{
    const COURSE_ID = 1;

    const NON_EXISTING_COURSE_ID = 999;

    /**
     * @var GetCourseCategory
     */
    protected $useCase;
}
