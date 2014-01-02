<?php

namespace SimpleIT\ClaireAppBundle\Controller\Course\Component;

use SimpleIT\AppBundle\Controller\AppController;
use OC\BusinessRules\Gateways\Course\Course\CourseNotFoundException;
use OC\BusinessRules\UseCases\Course\Workflow\DTO\ChangeCourseStatusRequestDTO;
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
            $this->get('simple_it.claire.use_cases.use_case_factory')
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
            $this->get('simple_it.claire.use_cases.use_case_factory')
                ->make('ChangeCourseToPublished')
                ->execute(new ChangeCourseStatusRequestDTO($courseId));

            return new Response();
        } catch (CourseNotFoundException $cnfe) {
            throw new NotFoundHttpException();
        }
    }
}
