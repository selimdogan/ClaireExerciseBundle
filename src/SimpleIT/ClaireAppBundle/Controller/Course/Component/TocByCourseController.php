<?php

namespace SimpleIT\ClaireAppBundle\Controller\Course\Component;

use SimpleIT\ApiResourcesBundle\Course\CourseResource;
use SimpleIT\ApiResourcesBundle\Course\PartResource;
use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\ClaireAppBundle\UseCases\Course\Toc\DTO\AddElementToTocRequestDTO;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\TocBuilder;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\TocBuilderForEdition;
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

        $tocVMBuilder = new TocBuilderForEdition($this->get('router'));
        $tocVM = $tocVMBuilder->buildTocForEdition($toc);

        return $this->render(
            'SimpleITClaireAppBundle:Course/Course/Component:tocEdit.html.twig',
            array('toc' => $tocVM, 'courseId' => $tocVM->id)
        );
    }

    /**
     * @return Response
     */
    public function editAction(Request $request, $courseId)
    {
        $useCaseRequest = new AddElementToTocRequestDTO();
        $useCaseRequest->courseId = $courseId;
        $parentId = $request->get('parentId');
        $useCaseRequest->parentId = $parentId;
        $useCaseResponse = $this->get('simple_it.claire.course.toc.add_element_to_toc')->execute(
            $useCaseRequest
        );

        $newElement = $useCaseResponse->getNewElement();
        if ($newElement->getSubtype() == PartResource::TITLE_1) {
            $url = $this->generateUrl(
                'simple_it_claire_course_course_edit',
                array('courseId' => $courseId)
            );
        } elseif ($newElement->getSubtype() == PartResource::TITLE_2) {
            $url = $this->generateUrl(
                'simple_it_claire_course_part_edit',
                array('courseId' => $courseId, 'partId' => $newElement->getId())
            );

        }

        return $this->redirect(
            $this->generateUrl($url)
        );
    }
}
