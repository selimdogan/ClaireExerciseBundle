<?php

namespace SimpleIT\ClaireAppBundle\Controller\Course\Component\Workflow;

use OC\CLAIRE\BusinessRules\Entities\Course\Course\Status;
use OC\CLAIRE\BusinessRules\Responders\AssociatedContent\Category\GetDraftCourseCategoryResponse;
use OC\CLAIRE\BusinessRules\Responders\Course\Course\GetCourseResponse;
use
    OC\CLAIRE\BusinessRules\UseCases\AssociatedContent\CategoryByCourse\DTO\GetDraftCourseCategoryRequestDTO;
use OC\CLAIRE\BusinessRules\UseCases\Course\Course\DTO\GetPublishedCourseRequestDTO;
use SimpleIT\AppBundle\Controller\AppController;
use OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException;
use OC\CLAIRE\BusinessRules\UseCases\Course\Workflow\DTO\ChangeCourseStatusRequestDTO;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class WorkflowController extends AppController
{
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

    public function changeCourseToPublishedAction($courseId)
    {
        try {
            $this->get('oc.claire.use_cases.course_use_case_factory')
                ->make('ChangeCourseToPublished')
                ->execute(new ChangeCourseStatusRequestDTO($courseId));

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
        } catch (CourseNotFoundException $cnfe) {
            throw new NotFoundHttpException();
        }
    }
}
