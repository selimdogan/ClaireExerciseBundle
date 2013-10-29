<?php

namespace SimpleIT\ClaireAppBundle\Controller\Course\Component;

use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\Utils\HTTP;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CourseContentController
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class CourseContentController extends AppController
{
    /**
     * Edit course content
     *
     * @param Request $request  Request
     * @param int     $courseId Course id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editViewAction(Request $request, $courseId)
    {
        $courseContent = null;

        if (HTTP::METHOD_GET == $request->getMethod()) {
            $courseContent = $this->get('simple_it.claire.course.course')->getContentToEdit(
                $courseId
            );
        } elseif (HTTP::METHOD_POST) {

        }

        return $this->render(
            'SimpleITClaireAppBundle:Course/Course/Component:editContent.html.twig',
            array(
                'courseId' => $courseId,
                'courseContent'    => $courseContent
            )
        );
    }
}
