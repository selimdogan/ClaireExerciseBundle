<?php

namespace SimpleIT\ClaireAppBundle\Controller\Course\Component;

use SimpleIT\AppBundle\Annotation\Cache;
use SimpleIT\ApiResourcesBundle\Course\CourseResource;
use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\Utils\RequestUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PartContentController
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class PartContentController extends AppController
{
    /**
     * View Part content
     *
     * @param int|string $courseIdentifier Course id | slug
     * @param int|string $partIdentifier   Part id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Cache (namespacePrefix="claire_app_course_course", namespaceAttribute="courseIdentifier", lifetime=0)
     */
    public function viewAction($courseIdentifier, $partIdentifier)
    {
        return new Response($this->get('simple_it.claire.course.part')->getContent(
            $courseIdentifier,
            $partIdentifier
        ));
    }

    /**
     * View Part content with status different of published
     *
     * @param Request      $request  Request
     * @param int | string $courseId Course id
     * @param int | string $partId   Part id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewToEditAction(Request $request, $courseId, $partId)
    {
        return new Response($this->get('simple_it.claire.course.part')->getContentToEdit(
            $courseId,
            $partId,
            $request->get(CourseResource::STATUS, CourseResource::STATUS_DRAFT)
        ));
    }

    /**
     * Edit part content
     *
     * @param Request      $request          Request
     * @param int | string $courseIdentifier Course id | slug
     * @param int | string $partIdentifier   Part id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editContentAction(Request $request, $courseIdentifier, $partIdentifier)
    {
        $partContent = null;
        $status = $request->get(CourseResource::STATUS, CourseResource::STATUS_DRAFT);
        if (RequestUtils::METHOD_GET == $request->getMethod()) {
            $partContent = $this->get('simple_it.claire.course.part')->getContentToEdit(
                $courseIdentifier,
                $partIdentifier,
                $status
            );
        } elseif (RequestUtils::METHOD_POST == $request->getMethod() && $request->isXmlHttpRequest()
        ) {
            $partContent = $request->get('partContent');
            $partContent = $this->get('simple_it.claire.course.part')->saveContent(
                $courseIdentifier,
                $partIdentifier,
                $partContent,
                $status
            );

            return new Response($partContent);
        }

        return $this->render(
            'SimpleITClaireAppBundle:Course/PartContent/Component:edit.html.twig',
            array(
                'courseIdentifier' => $courseIdentifier,
                'partIdentifier'   => $partIdentifier,
                'partContent'      => $partContent
            )
        );
    }
}
