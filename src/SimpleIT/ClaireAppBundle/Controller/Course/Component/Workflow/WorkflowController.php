<?php

namespace SimpleIT\ClaireAppBundle\Controller\Course\Component\Workflow;

use OC\CLAIRE\BusinessRules\Entities\Course\Course\Status;
use OC\CLAIRE\BusinessRules\Responders\AssociatedContent\Category\GetDraftCourseCategoryResponse;
use OC\CLAIRE\BusinessRules\Responders\Course\Course\GetCourseResponse;
use
    OC\CLAIRE\BusinessRules\UseCases\AssociatedContent\CategoryByCourse\DTO\GetDraftCourseCategoryRequestDTO;
use OC\CLAIRE\BusinessRules\UseCases\Course\Course\DTO\GetPublishedCourseRequestDTO;
use
    OC\CLAIRE\BusinessRules\UseCases\Course\Workflow\DTO\DismissWaitingForPublicationCourseRequestDTO;
use SimpleIT\AppBundle\Controller\AppController;
use OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException;
use OC\CLAIRE\BusinessRules\UseCases\Course\Workflow\DTO\ChangeCourseStatusRequestDTO;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use JMS\SecurityExtraBundle\Annotation\Secure;;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class WorkflowController extends AppController
{
    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function changeCourseToWaitingForPublicationAction($courseId)
    {
        try {
            $this->get('oc.claire.use_cases.course_use_case_factory')
                ->make('ChangeCourseToWaitingForPublication')
                ->execute(new ChangeCourseStatusRequestDTO($courseId));

            /** @var GetDraftCourseCategoryResponse $ucResponse */
            $ucResponse = $this->get('oc.claire.use_cases.associated_content_use_case_factory')
                ->make('GetDraftCourseCategory')
                ->execute(new GetDraftCourseCategoryRequestDTO($courseId));

            return $this->redirect(
                $this->generateUrl(
                    'simple_it_claire_course_course_view',
                    array(
                        'courseIdentifier'   => $courseId,
                        'categoryIdentifier' => $ucResponse->getCategorySlug(),
                        'status'             => Status::WAITING_FOR_PUBLICATION
                    )
                )
            );
        } catch (CourseNotFoundException $cnfe) {
            throw new NotFoundHttpException();
        }
    }

    /**
     * @Secure(roles="ROLE_PUBLISH_ALL_COURSES")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function publishWaitingForPublicationCourseAction($courseId)
    {
        try {
            $this->get('oc.claire.use_cases.course_use_case_factory')
                ->make('PublishWaitingForPublicationCourse')
                ->execute(new ChangeCourseStatusRequestDTO($courseId));

            return $this->redirectToPublishedCourse($courseId);
        } catch (CourseNotFoundException $cnfe) {
            throw new NotFoundHttpException();
        }
    }

    private function redirectToPublishedCourse($courseId)
    {
        /** @var GetCourseResponse $courseResponse */
        $courseResponse = $this->get('oc.claire.use_cases.course_use_case_factory')
            ->make('GetPublishedCourse')
            ->execute(new GetPublishedCourseRequestDTO($courseId));

        /** @var GetDraftCourseCategoryResponse $categoryResponse */
        $categoryResponse = $this->get('oc.claire.use_cases.associated_content_use_case_factory')
            ->make('GetDraftCourseCategory')
            ->execute(new GetDraftCourseCategoryRequestDTO($courseId));

        return $this->redirect(
            $this->generateUrl(
                'simple_it_claire_course_course_view',
                array(
                    'courseIdentifier'   => $courseResponse->getSlug(),
                    'categoryIdentifier' => $categoryResponse->getCategorySlug()
                )
            )
        );
    }

    /**
     * @Secure(roles="ROLE_PUBLISH_ALL_COURSES")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function publishDraftCourseAction($courseId)
    {
        try {
            $this->get('oc.claire.use_cases.course_use_case_factory')
                ->make('PublishDraftCourse')
                ->execute(new ChangeCourseStatusRequestDTO($courseId));

            return $this->redirectToPublishedCourse($courseId);
        } catch (CourseNotFoundException $cnfe) {
            throw new NotFoundHttpException();
        }
    }

    /**
     * @Secure(roles="ROLE_PUBLISH_ALL_COURSES")
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function dismissWaitingForPublicationCourseAction($courseId)
    {
        try {
            $this->get('oc.claire.use_cases.course_use_case_factory')
                ->make('DismissWaitingForPublication')
                ->execute(new DismissWaitingForPublicationCourseRequestDTO($courseId));

            return new Response();
        } catch (CourseNotFoundException $cnfe) {
            throw new NotFoundHttpException();
        }
    }
}
