<?php

namespace SimpleIT\ClaireAppBundle\Controller\Course\Component;

use SimpleIT\AppBundle\Annotation\Cache;
use SimpleIT\ApiResourcesBundle\Course\CourseResource;
use SimpleIT\AppBundle\Controller\AppController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CourseContentController
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class CourseContentController extends AppController
{
    /**
     * View content
     *
     * @param int|string $courseIdentifier Course id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Cache (namespacePrefix="claire_app_course_course", namespaceAttribute="courseIdentifier", lifetime=0)
     */
    public function viewAction($courseIdentifier)
    {
        $content = $this->get('simple_it.claire.course.course')->getContent($courseIdentifier);

        return $this->render(
            'SimpleITClaireAppBundle:Course/Course/Component:viewContent.html.twig',
            array('content' => $content)
        );
    }

    /**
     * View content with status different of published
     *
     * @param Request $request  Request
     * @param int     $courseId Course id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewByStatusAction(Request $request, $courseId)
    {
        $status = $request->get(CourseResource::STATUS, CourseResource::STATUS_DRAFT);
        $content = $this->get('simple_it.claire.course.course')->getContentToEdit(
            $courseId,
            $status
        );

        return $this->render(
            'SimpleITClaireAppBundle:Course/Course/Component:viewContent.html.twig',
            array('content' => $content)
        );
    }

    /**
     * Edit course content (GET)
     *
     * @param Request $request  Request
     * @param int     $courseId Course id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editViewAction(Request $request, $courseId)
    {
        $courseContent = $this->get('simple_it.claire.course.course')->getContentToEdit(
            $courseId,
            $request->get(CourseResource::STATUS, CourseResource::STATUS_DRAFT)
        );

        return $this->render(
            'SimpleITClaireAppBundle:Course/Course/Component:editContent.html.twig',
            array(
                'content' => $courseContent,
                'action'  =>
                    $this->generateUrl(
                        'simple_it_claire_component_course_course_content_edit',
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
        $content = $request->get('content');
        $content = $this->get('simple_it.claire.course.course')->saveContent(
            $courseId,
            $content,
            $request->get(CourseResource::STATUS, CourseResource::STATUS_DRAFT)
        );

        return new Response($content);
    }
}
