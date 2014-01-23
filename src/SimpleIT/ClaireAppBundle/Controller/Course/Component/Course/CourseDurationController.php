<?php

namespace SimpleIT\ClaireAppBundle\Controller\Course\Component\Course;

use OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException;
use OC\CLAIRE\BusinessRules\Responders\Course\CourseDuration\GetDraftCourseDurationResponse;
use OC\CLAIRE\BusinessRules\UseCases\Course\CourseDuration\DTO\GetDraftCourseDurationRequestDTO;
use OC\CLAIRE\BusinessRules\UseCases\Course\CourseDuration\DTO\SaveCourseDurationRequestDTO;
use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\ClaireAppBundle\Form\Course\Model\CourseDurationModel;
use SimpleIT\ClaireAppBundle\Form\Course\Type\CourseDurationType;
use SimpleIT\Utils\HTTP;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CourseDurationController extends AppController
{
    public function editViewAction($courseId)
    {
        try {
            /** @var GetDraftCourseDurationResponse $ucResponse */
            $ucResponse = $this->get('oc.claire.use_cases.course_use_case_factory')
                ->make('GetDraftCourseDuration')
                ->execute(new GetDraftCourseDurationRequestDTO($courseId));

            $form = $this->createForm(
                new CourseDurationType(),
                new CourseDurationModel($ucResponse->getCourseDuration())
            );

            return $this->render(
                'SimpleITClaireAppBundle:Course/Course/partial:editDuration.html.twig',
                array(
                    'actionUrl' => $this->generateUrl(
                            'simple_it_claire_course_component_course_duration_edit',
                            array('courseId' => $courseId)
                        ),
                    'form'      => $form->createView()
                )
            );

        } catch (CourseNotFoundException $cnfe) {
            throw new NotFoundHttpException();
        }
    }

    public function editAction(Request $request, $courseId)
    {
        $form = $this->createForm(
            new CourseDurationType(),
            $duration = new CourseDurationModel()
        );
        $form->bind($request);
        if ($form->isValid()) {
            $this->get('oc.claire.use_cases.course_use_case_factory')
                ->make('SaveCourseDuration')->execute(
                    new SaveCourseDurationRequestDTO($courseId, $duration->getDuration())
                );
        } else {
            throw new HttpException(HTTP::STATUS_CODE_BAD_REQUEST, $form->getErrors());
        }

        return new JsonResponse();
    }
}
