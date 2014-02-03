<?php

namespace SimpleIT\ClaireAppBundle\Controller\Course\Component\Course;

use OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException;
use OC\CLAIRE\BusinessRules\Responders\Course\CourseDescription\GetDraftCourseDescriptionResponse;
use
    OC\CLAIRE\BusinessRules\UseCases\Course\CourseDescription\DTO\GetDraftCourseDescriptionRequestDTO;
use OC\CLAIRE\BusinessRules\UseCases\Course\CourseDescription\DTO\SaveCourseDescriptionRequestDTO;
use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\ClaireAppBundle\Form\Course\Model\CourseDescriptionModel;
use SimpleIT\ClaireAppBundle\Form\Course\Type\CourseDescriptionType;
use SimpleIT\Utils\HTTP;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CourseDescriptionController extends AppController
{
    public function editViewAction($courseId)
    {
        try {
            /** @var GetDraftCourseDescriptionResponse $ucResponse */
            $ucResponse = $this->get('oc.claire.use_cases.course_use_case_factory')
                ->make('GetDraftCourseDescription')
                ->execute(new GetDraftCourseDescriptionRequestDTO($courseId));

            $form = $this->createForm(
                new CourseDescriptionType(),
                new CourseDescriptionModel($ucResponse->getCourseDescription())
            );

            return $this->render(
                'SimpleITClaireAppBundle:Course/Common/partial:editDescription.html.twig',
                array(
                    'actionUrl' => $this->generateUrl(
                            'simple_it_claire_course_component_course_description_edit',
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
            new CourseDescriptionType(),
            $description = new CourseDescriptionModel()
        );
        $form->bind($request);
        if ($form->isValid()) {
            $this->get('oc.claire.use_cases.course_use_case_factory')
                ->make('SaveCourseDescription')->execute(
                    new SaveCourseDescriptionRequestDTO($courseId, $description->getDescription())
                );
        } else {
            throw new HttpException(HTTP::STATUS_CODE_BAD_REQUEST, $form->getErrors());
        }

        return new JsonResponse();
    }
}