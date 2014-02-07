<?php

namespace SimpleIT\ClaireAppBundle\UseCaseFactory;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CourseUseCaseFactoryImplTest extends UseCaseFactoryTest
{
    protected $useCases = array(
        'GetDraftCourse'                        => 'OC\CLAIRE\BusinessRules\UseCases\Course\Course\GetDraftCourse',
        'GetWaitingForPublicationCourse'        => 'OC\CLAIRE\BusinessRules\UseCases\Course\Course\GetWaitingForPublicationCourse',
        'GetPublishedCourse'                    => 'OC\CLAIRE\BusinessRules\UseCases\Course\Course\GetPublishedCourse',
        'GetCourseStatuses'                     => 'OC\CLAIRE\BusinessRules\UseCases\Course\Course\GetCourseStatuses',
        'SaveCourse'                            => 'OC\CLAIRE\BusinessRules\UseCases\Course\Course\SaveCourse',
        'GetDraftCourseContent'                 => 'OC\CLAIRE\BusinessRules\UseCases\Course\CourseContent\GetDraftCourseContent',
        'GetWaitingForPublicationCourseContent' => 'OC\CLAIRE\BusinessRules\UseCases\Course\CourseContent\GetWaitingForPublicationCourseContent',
        'GetPublishedCourseContent'             => 'OC\CLAIRE\BusinessRules\UseCases\Course\CourseContent\GetPublishedCourseContent',
        'SaveCourseContent'                     => 'OC\CLAIRE\BusinessRules\UseCases\Course\CourseContent\SaveCourseContent',
        'ChangeCourseToWaitingForPublication'   => 'OC\CLAIRE\BusinessRules\UseCases\Course\Workflow\ChangeCourseToWaitingForPublication',
        'PublishDraftCourse'                    => 'OC\CLAIRE\BusinessRules\UseCases\Course\Workflow\PublishDraftCourse',
        'PublishWaitingForPublicationCourse'    => 'OC\CLAIRE\BusinessRules\UseCases\Course\Workflow\PublishWaitingForPublicationCourse',
        'DismissWaitingForPublicationCourse'    => 'OC\CLAIRE\BusinessRules\UseCases\Course\Workflow\DismissWaitingForPublicationCourse',
        'AddElementToToc'                       => 'OC\CLAIRE\BusinessRules\UseCases\Course\Toc\AddElementToToc',
        'GetDraftDisplayLevel'                  => 'OC\CLAIRE\BusinessRules\UseCases\Course\DisplayLevel\GetDraftDisplayLevel',
        'SaveDisplayLevel'                      => 'OC\CLAIRE\BusinessRules\UseCases\Course\DisplayLevel\SaveDisplayLevel',
        'GetDraftCourseDifficulty'              => 'OC\CLAIRE\BusinessRules\UseCases\Course\CourseDifficulty\GetDraftCourseDifficulty',
        'SaveCourseDifficulty'                  => 'OC\CLAIRE\BusinessRules\UseCases\Course\CourseDifficulty\SaveCourseDifficulty',
        'GetDraftCourseDuration'                => 'OC\CLAIRE\BusinessRules\UseCases\Course\CourseDuration\GetDraftCourseDuration',
        'SaveCourseDuration'                    => 'OC\CLAIRE\BusinessRules\UseCases\Course\CourseDuration\SaveCourseDuration',
        'GetDraftCourseDescription'             => 'OC\CLAIRE\BusinessRules\UseCases\Course\CourseDescription\GetDraftCourseDescription',
        'SaveCourseDescription'                 => 'OC\CLAIRE\BusinessRules\UseCases\Course\CourseDescription\SaveCourseDescription',
    );

    protected function setup()
    {
        $this->useCaseFactory = new CourseUseCaseFactoryImpl();
        parent::setup();
    }
}
