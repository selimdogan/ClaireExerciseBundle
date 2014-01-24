<?php

namespace SimpleIT\ClaireAppBundle\UseCaseFactory;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class AssociatedContentFactoryImplTest extends UseCaseFactoryTest
{
    protected $useCases = array(
        'GetDraftCourseCategory'             => 'OC\CLAIRE\BusinessRules\UseCases\AssociatedContent\CategoryByCourse\GetDraftCourseCategory',
        'SaveCourseCategory'                 => 'OC\CLAIRE\BusinessRules\UseCases\AssociatedContent\CategoryByCourse\SaveCourseCategory',
        'GetDraftCourseTags'                 => 'OC\CLAIRE\BusinessRules\UseCases\AssociatedContent\TagByCourse\GetDraftCourseTags',
        'GetWaitingForPublicationCourseTags' => 'OC\CLAIRE\BusinessRules\UseCases\AssociatedContent\TagByCourse\GetWaitingForPublicationCourseTags',
        'GetPublishedCourseTags'             => 'OC\CLAIRE\BusinessRules\UseCases\AssociatedContent\TagByCourse\GetPublishedCourseTags',
    );

    protected function setup()
    {
        $this->useCaseFactory = new AssociatedContentUseCaseFactoryImpl();
        parent::setup();
    }
}
