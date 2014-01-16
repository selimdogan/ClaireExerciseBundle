<?php

namespace SimpleIT\ClaireAppBundle\Controller\Course\Component\Course;

use OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException;
use OC\CLAIRE\BusinessRules\Responders\Course\Course\GetCourseResponse;
use OC\CLAIRE\BusinessRules\UseCases\Course\Course\DTO\GetDraftCourseRequestDTO;
use SimpleIT\ApiResourcesBundle\Course\CourseResource;
use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\ClaireAppBundle\Form\Course\Model\CourseTitleModel;
use SimpleIT\ClaireAppBundle\Form\Course\Type\CourseTitleType;
use SimpleIT\Utils\HTTP;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CourseTitleController extends AppController
{
    public function editViewAction($courseId)
    {
        try {
            /** @var GetCourseResponse $ucResponse */
            $ucResponse =
                $this->get('oc.claire.use_cases.course_use_case_factory')
                    ->make('GetDraftCourse')
                    ->execute(new GetDraftCourseRequestDTO($courseId));

            $form = $this->createForm(
                new CourseTitleType(),
                new CourseTitleModel($ucResponse->getTitle())
            );

            return $this->render(
                'SimpleITClaireAppBundle:Course/Course/partial:editTitle.html.twig',
                array(
                    'actionUrl' => $this->generateUrl(
                            'simple_it_claire_course_component_course_title_edit',
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
            new CourseTitleType(),
            $title = new CourseTitleModel()
        );
        $form->bind($request);
        if ($form->isValid()) {
            // FIXME do usecase
//            $this->get('oc.claire.use_cases.course_use_case_factory')
//                ->make('SaveCourseTitle')->execute(
//                    new SaveCourseTitleRequestDTO($courseId, $title->getTitle())
//                );
            $course = new CourseResource();
            $course->setTitle($title->getTitle());
            $this->get('simple_it.claire.course.course')->save(
                $courseId,
                $course,
                $request->get(CourseResource::STATUS, CourseResource::STATUS_DRAFT)
            );
        } else {
            throw new HttpException(HTTP::STATUS_CODE_BAD_REQUEST, $form->getErrors());
        }

        return new JsonResponse();
    }
}
