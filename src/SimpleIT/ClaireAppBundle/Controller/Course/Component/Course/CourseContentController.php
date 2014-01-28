<?php

namespace SimpleIT\ClaireAppBundle\Controller\Course\Component\Course;

use OC\CLAIRE\BusinessRules\Responders\Course\CourseContent\GetCourseContentResponse;
use OC\CLAIRE\BusinessRules\Responders\Course\CourseContent\SaveCourseContentResponse;
use OC\CLAIRE\BusinessRules\UseCases\Course\CourseContent\DTO\SaveCourseContentRequestDTO;
use SimpleIT\AppBundle\Controller\AppController;
use OC\CLAIRE\BusinessRules\UseCases\Course\Course\DTO\GetDraftCourseRequestDTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class CourseContentController extends AppController
{
    public function editViewAction($courseId)
    {
        /** @var GetCourseContentResponse $ucResponse */
        $ucResponse =
            $this->get('oc.claire.use_cases.course_use_case_factory')
                ->make('GetDraftCourseContent')
                ->execute(new GetDraftCourseRequestDTO($courseId));

        return $this->render(
            'SimpleITClaireAppBundle:Course/Common/partial:editContent.html.twig',
            array(
                'content' => $ucResponse->getContent(),
                'action'  =>
                    $this->generateUrl(
                        'simple_it_claire_course_component_course_content_edit',
                        array('courseId' => $courseId)
                    )
            )
        );
    }

    public function editAction(Request $request, $courseId)
    {
        /** @var SaveCourseContentResponse $ucResponse */
        $ucResponse =
            $this->get('oc.claire.use_cases.course_use_case_factory')
                ->make('SaveCourseContent')
                ->execute(
                    new SaveCourseContentRequestDTO($courseId, $content = $request->get('content'))
                );

        return new Response($ucResponse->getContent());
    }
}
