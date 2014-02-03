<?php

namespace SimpleIT\ClaireAppBundle\UseCaseFactory;

use OC\CLAIRE\BusinessRules\Requestors\InvalidUseCaseException;
use OC\CLAIRE\BusinessRules\Requestors\UseCase;
use OC\CLAIRE\BusinessRules\Requestors\UseCaseFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CourseUseCaseFactoryImpl implements UseCaseFactory
{
    /**
     * @var ContainerInterface
     */
    private $injector;

    /**
     * @return UseCase
     */
    public function make($useCaseName)
    {
        switch ($useCaseName) {
            // COURSE
            case 'GetPublishedCourse':
                $useCase = $this->injector->get(
                    'oc.claire.use_cases.course.course.get_published_course'
                );
                break;
            case 'GetWaitingForPublicationCourse':
                $useCase = $this->injector->get(
                    'oc.claire.use_cases.course.course.get_waiting_for_publication_course'
                );
                break;
            case 'GetDraftCourse':
                $useCase = $this->injector->get(
                    'oc.claire.use_cases.course.course.get_draft_course'
                );
                break;
            case 'SaveCourse':
                $useCase = $this->injector->get(
                    'oc.claire.use_cases.course.course.save_course'
                );
                break;
            // CONTENT
            case 'GetPublishedCourseContent':
                $useCase = $this->injector->get(
                    'oc.claire.use_cases.course.course.get_published_course_content'
                );
                break;
            case 'GetWaitingForPublicationCourseContent':
                $useCase = $this->injector->get(
                    'oc.claire.use_cases.course.course.get_waiting_for_publication_course_content'
                );
                break;
            case 'GetDraftCourseContent':
                $useCase = $this->injector->get(
                    'oc.claire.use_cases.course.course.get_draft_course_content'
                );
                break;
            case 'SaveCourseContent':
                $useCase = $this->injector->get(
                    'oc.claire.use_cases.course.course.save_course_content'
                );
                break;
            // WORKFLOW
            case 'PublishDraftCourse':
                $useCase = $this->injector->get(
                    'oc.claire.use_cases.course.workflow.publish_draft_course'
                );
                break;
            case 'PublishWaitingForPublicationCourse':
                $useCase = $this->injector->get(
                    'oc.claire.use_cases.course.workflow.publish_waiting_for_publication_course'
                );
                break;
            case 'ChangeCourseToWaitingForPublication':
                $useCase = $this->injector->get(
                    'oc.claire.use_cases.course.workflow.change_course_to_waiting_for_publication'
                );
                break;
            // DISPLAY LEVEL
            case 'GetDraftDisplayLevel':
                $useCase = $this->injector->get(
                    'oc.claire.use_cases.course.display_level.get_draft_display_level'
                );
                break;
            case 'SaveDisplayLevel':
                $useCase = $this->injector->get(
                    'oc.claire.use_cases.course.display_level.save_display_level'
                );
                break;
            // DESCRIPTION
            case 'GetDraftCourseDescription':
                $useCase = $this->injector->get(
                    'oc.claire.use_cases.course.description.get_draft_course_description'
                );
                break;
            case 'SaveCourseDescription':
                $useCase = $this->injector->get(
                    'oc.claire.use_cases.course.description.save_course_description'
                );
                break;
            // DIFFICULTY
            case 'GetDraftCourseDifficulty':
                $useCase = $this->injector->get(
                    'oc.claire.use_cases.course.difficulty.get_draft_course_difficulty'
                );
                break;
            case 'SaveCourseDifficulty':
                $useCase = $this->injector->get(
                    'oc.claire.use_cases.course.difficulty.save_course_difficulty'
                );
                break;
            // DURATION
            case 'GetDraftCourseDuration':
                $useCase = $this->injector->get(
                    'oc.claire.use_cases.course.duration.get_draft_course_duration'
                );
                break;
            case 'SaveCourseDuration':
                $useCase = $this->injector->get(
                    'oc.claire.use_cases.course.duration.save_course_duration'
                );
                break;
            // TOC
            case 'AddElementToToc':
                $useCase = $this->injector->get(
                    'oc.claire.use_cases.course.toc.add_element_to_toc'
                );
                break;
            default:
                throw new InvalidUseCaseException($useCaseName);
        }

        return $useCase;
    }

    public function setInjector(ContainerInterface $injector)
    {
        $this->injector = $injector;
    }
}
