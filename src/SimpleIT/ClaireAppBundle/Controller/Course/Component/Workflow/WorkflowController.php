<?php

namespace SimpleIT\ClaireAppBundle\Controller\Course\Component\Workflow;

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

            return new Response();
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

            return new Response();
        } catch (CourseNotFoundException $cnfe) {
            throw new NotFoundHttpException();
        }
    }
}
