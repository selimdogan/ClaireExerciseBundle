<?php

namespace SimpleIT\ClaireAppBundle\Controller\Course\Component;

use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\ClaireAppBundle\Gateways\Course\Course\CourseNotFoundException;
use SimpleIT\ClaireAppBundle\UseCases\Course\Workflow\DTO\ChangeCourseStatusRequestDTO;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class WorkflowController extends AppController
{
    public function changeCourseToWaitingForPublication($courseId)
    {
        try {
            $ucRequest = new ChangeCourseStatusRequestDTO($courseId);
            $this->get(
                'simple_it.claire.use_cases.course.workflow.change_course_to_waiting_for_publication'
            )->execute($ucRequest);

            return new Response();
        } catch (CourseNotFoundException $cnfe) {
            throw new NotFoundHttpException();
        }
    }

    public function changeCourseToPublished($courseId)
    {
        try {
            $ucRequest = new ChangeCourseStatusRequestDTO($courseId);
            $this->get(
                'simple_it.claire.use_cases.course.workflow.change_course_to_published'
            )->execute($ucRequest);

            return new Response();
        } catch (CourseNotFoundException $cnfe) {
            throw new NotFoundHttpException();
        }
    }
}
