<?php

namespace SimpleIT\ClaireAppBundle\Controller\Course\Component\Course;

use OC\CLAIRE\BusinessRules\Responders\Course\Content\GetContentResponse;
use SimpleIT\AppBundle\Controller\AppController;
use OC\CLAIRE\BusinessRules\Responders\Course\Content\SaveContentResponse;
use OC\CLAIRE\BusinessRules\UseCases\Course\Content\DTO\SaveContentRequestDTO;
use OC\CLAIRE\BusinessRules\UseCases\Course\Course\DTO\GetDraftCourseRequestDTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class CourseContentController extends AppController
{
    /**
     * Edit course content (GET)
     *
     * @param int $courseId Course id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editViewAction($courseId)
    {
        /** @var GetContentResponse $ucResponse */
        $ucResponse = $this->get('oc.claire.use_cases.course_use_case_factory')
            ->make('GetDraftContent')
            ->execute(new GetDraftCourseRequestDTO($courseId));

        return $this->render(
            'SimpleITClaireAppBundle:Course/Course/Component:editContent.html.twig',
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

    /**
     * Edit course content (POST)
     *
     * @param Request $request  Request
     * @param int     $courseId Course id
     *
     * @return Response
     */
    public function editAction(Request $request, $courseId)
    {
        /** @var SaveContentResponse $ucResponse */
        $ucResponse = $this->get('oc.claire.use_cases.course_use_case_factory')
            ->make('SaveContent')
            ->execute(new SaveContentRequestDTO($courseId, $content = $request->get('content')));

        return new Response($ucResponse->getContent());
    }
}
