<?php

namespace SimpleIT\ClaireAppBundle\Controller\Course\Component;

use SimpleIT\ApiResourcesBundle\Course\CourseResource;
use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\TocBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TocByCourseController
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class TocByCourseController extends AppController
{
    /**
     * @param int $courseId Course id
     */
    public function editViewAction(Request $request, $courseId)
    {
        $toc = $this->get('simple_it.claire.course.course')->getTocByStatus(
            $courseId,
            $status = $request->get(CourseResource::STATUS, CourseResource::STATUS_DRAFT)
        );

        $tocVMBuilder = new TocBuilder($this->get('router'));
        $tocVM = $tocVMBuilder->buildTocForEdition($toc);

        return $this->render(
            'SimpleITClaireAppBundle:Course/Course/Component:tocEdit.html.twig',
            array('toc' => $tocVM)
        );
    }

    public function editAction()
    {
        return new Response();
    }
}
