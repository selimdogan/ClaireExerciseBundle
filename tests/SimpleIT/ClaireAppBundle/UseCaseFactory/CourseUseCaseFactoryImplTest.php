<?php

namespace SimpleIT\ClaireAppBundle\UseCaseFactory;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CourseUseCaseFactoryImplTest extends UseCaseFactoryTest
{
    protected $useCases = array(
        'GetDraftCourse'                      => 'OC\CLAIRE\BusinessRules\UseCases\Course\Course\GetDraftCourse',
        'GetWaitingForPublicationCourse'      => 'OC\CLAIRE\BusinessRules\UseCases\Course\Course\GetWaitingForPublicationCourse',
        'GetPublishedCourse'                  => 'OC\CLAIRE\BusinessRules\UseCases\Course\Course\GetPublishedCourse',
        'GetDraftContent'                     => 'OC\CLAIRE\BusinessRules\UseCases\Course\Content\GetDraftContent',
        'GetWaitingForPublicationContent'     => 'OC\CLAIRE\BusinessRules\UseCases\Course\Content\GetWaitingForPublicationContent',
        'GetPublishedContent'                 => 'OC\CLAIRE\BusinessRules\UseCases\Course\Content\GetPublishedContent',
        'SaveContent'                         => 'OC\CLAIRE\BusinessRules\UseCases\Course\Content\SaveContent',
        'ChangeCourseToWaitingForPublication' => 'OC\CLAIRE\BusinessRules\UseCases\Course\Workflow\ChangeCourseToWaitingForPublication',
        'PublishDraftCourse'                  => 'OC\CLAIRE\BusinessRules\UseCases\Course\Workflow\PublishDraftCourse',
        'PublishWaitingForPublicationCourse'  => 'OC\CLAIRE\BusinessRules\UseCases\Course\Workflow\PublishWaitingForPublicationCourse',
        'GetDraftDisplayLevel'                => 'OC\CLAIRE\BusinessRules\UseCases\Course\DisplayLevel\GetDraftDisplayLevel',
        'SaveDisplayLevel'                    => 'OC\CLAIRE\BusinessRules\UseCases\Course\DisplayLevel\SaveDisplayLevel',
        'GetDraftCourseDifficulty'            => 'OC\CLAIRE\BusinessRules\UseCases\Course\CourseDifficulty\GetDraftCourseDifficulty',
        'SaveCourseDifficulty'                => 'OC\CLAIRE\BusinessRules\UseCases\Course\CourseDifficulty\SaveCourseDifficulty',
        'GetDraftCourseDuration'              => 'OC\CLAIRE\BusinessRules\UseCases\Course\CourseDuration\GetDraftCourseDuration',
        'SaveCourseDuration'                  => 'OC\CLAIRE\BusinessRules\UseCases\Course\CourseDuration\SaveCourseDuration',
        'GetDraftCourseDescription'           => 'OC\CLAIRE\BusinessRules\UseCases\Course\CourseDescription\GetDraftCourseDescription',
        'SaveCourseDescription'               => 'OC\CLAIRE\BusinessRules\UseCases\Course\CourseDescription\SaveCourseDescription',
        'AddElementToToc'                     => 'OC\CLAIRE\BusinessRules\UseCases\Course\Toc\AddElementToToc',
    );

    protected function setup()
    {
        $this->useCaseFactory = new CourseUseCaseFactoryImpl();
        parent::setup();
    }
}
