<?php
/*
 * Copyright 2013 Simple IT
 *
 * This file is part of CLAIRE.
 *
 * CLAIRE is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * CLAIRE is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with CLAIRE. If not, see <http://www.gnu.org/licenses/>
 */

namespace SimpleIT\ClaireAppBundle\Controller\Course\Component;

use SimpleIT\ApiResourcesBundle\Course\CourseResource;
use SimpleIT\AppBundle\Annotation\Cache;
use SimpleIT\ApiResourcesBundle\Course\PartResource;
use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\AppBundle\Model\AppResponse;
use SimpleIT\AppBundle\Util\RequestUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PartController
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class PartController extends AppController
{
    /**
     * @param int|string $courseIdentifier Course id | slug
     * @param int|string $partIdentifier   Part id | slug
     *
     * @return Response
     * @Cache (namespacePrefix="claire_app_course_course", namespaceAttribute="courseIdentifier", lifetime=0)
     */
    public function viewAction($courseIdentifier, $partIdentifier)
    {
        $part = $this->get('simple_it.claire.course.part')->get($courseIdentifier, $partIdentifier);

        return $this->render(
            'SimpleITClaireAppBundle:Course/Part/Component:view.html.twig',
            array('part' => $part)
        );
    }

    /**
     * Edit a part
     *
     * @param Request      $request          Request
     * @param int | string $courseIdentifier Course id | slug
     * @param int | string $partIdentifier   Part id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $courseId, $partId)
    {
        $part = new PartResource();
        if (RequestUtils::METHOD_GET == $request->getMethod()) {
            $part = $this->get('simple_it.claire.course.part')->getByStatus(
                $courseId,
                $partId,
                CourseResource::STATUS_DRAFT
            );
        }

        $form = $this->createFormBuilder($part)
            ->add('title')
            ->getForm();

        if (RequestUtils::METHOD_POST == $request->getMethod() && $request->isXmlHttpRequest()) {
            $form->bind($request);
            if ($form->isValid()) {
                $part = $this->get('simple_it.claire.course.part')->save(
                    $courseId,
                    $partId,
                    $part
                );
            }

            return new AppResponse($part);
        }

        return $this->render(
            'SimpleITClaireAppBundle:Course/Part/Component:edit.html.twig',
            array(
                'courseId' => $courseId,
                'partId'   => $partId,
                'part'     => $part,
                'form'     => $form->createView()
            )
        );
    }

    /**
     * View table of content Medium
     *
     * @param int | string $courseIdentifier   Course id | slug
     * @param int | string $partIdentifier     Current part id | slug
     * @param int | string $categoryIdentifier Category id | slug
     * @param int          $displayLevel       Course display level
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Cache (namespacePrefix="claire_app_course_course", namespaceAttribute="courseIdentifier", lifetime=0)
     */
    public function viewTocMediumAction(
        $courseIdentifier,
        $partIdentifier,
        $categoryIdentifier,
        $displayLevel
    )
    {
        $toc = $this->get('simple_it.claire.course.part')->getToc(
            $courseIdentifier,
            $partIdentifier
        );

        return $this->render(
            'SimpleITClaireAppBundle:Course/Course/Component:viewTocMedium.html.twig',
            array(
                'toc'                => $toc,
                'courseIdentifier'   => $courseIdentifier,
                'categoryIdentifier' => $categoryIdentifier,
                'displayLevel'       => $displayLevel
            )
        );
    }

    /**
     * View table of content Medium
     *
     * @param Request      $request            Request
     * @param int | string $courseIdentifier   Course id | slug
     * @param int | string $partIdentifier     Current part id | slug
     * @param int | string $categoryIdentifier Category id | slug
     * @param int          $displayLevel       Course display level
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewTocMediumByStatusAction(
        Request $request,
        $courseIdentifier,
        $partIdentifier,
        $categoryIdentifier,
        $displayLevel
    )
    {
        $toc = $this->get('simple_it.claire.course.part')->getTocByStatus(
            $courseIdentifier,
            $partIdentifier,
            $request->get(CourseResource::STATUS, CourseResource::STATUS_DRAFT)
        );

        return $this->render(
            'SimpleITClaireAppBundle:Course/Course/Component:viewTocMedium.html.twig',
            array(
                'toc'                => $toc,
                'courseIdentifier'   => $courseIdentifier,
                'categoryIdentifier' => $categoryIdentifier,
                'displayLevel'       => $displayLevel
            )
        );
    }

    /**
     * View table of content BIG
     *
     * @param int|string $courseIdentifier   Course id | slug
     * @param int|string $partIdentifier     Current part id | slug
     * @param int|string $categoryIdentifier Category id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Cache (namespacePrefix="claire_app_course_course", namespaceAttribute="courseIdentifier", lifetime=0)
     */
    public function viewTocBigAction($courseIdentifier, $partIdentifier, $categoryIdentifier)
    {
        $toc = $this->get('simple_it.claire.course.part')->getToc(
            $courseIdentifier,
            $partIdentifier
        );

        return $this->render(
            'SimpleITClaireAppBundle:Course/Course/Component:viewTocBig.html.twig',
            array(
                'toc'                => $toc,
                'courseIdentifier'   => $courseIdentifier,
                'categoryIdentifier' => $categoryIdentifier
            )
        );
    }

    /**
     * View table of content BIG
     *
     * @param Request    $request            Request
     * @param int|string $courseIdentifier   Course id | slug
     * @param int|string $partIdentifier     Current part id | slug
     * @param int|string $categoryIdentifier Category id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewTocBigByStatusAction(
        Request $request,
        $courseIdentifier,
        $partIdentifier,
        $categoryIdentifier
    )
    {
        $toc = $this->get('simple_it.claire.course.part')->getTocByStatus(
            $courseIdentifier,
            $partIdentifier,
            $request->get(CourseResource::STATUS, CourseResource::STATUS_DRAFT)
        );

        return $this->render(
            'SimpleITClaireAppBundle:Course/Course/Component:viewTocBig.html.twig',
            array(
                'toc'                => $toc,
                'courseIdentifier'   => $courseIdentifier,
                'categoryIdentifier' => $categoryIdentifier
            )
        );
    }

    /**
     * View introduction
     *
     * @param int|string $courseIdentifier Course id | slug
     * @param int|string $partIdentifier   Part id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Cache (namespacePrefix="claire_app_course_course", namespaceAttribute="courseIdentifier", lifetime=0)
     */
    public function viewIntroductionAction($courseIdentifier, $partIdentifier)
    {
        $introduction = $this->get('simple_it.claire.course.part')->getIntroduction(
            $courseIdentifier,
            $partIdentifier
        );

        return $this->render(
            'SimpleITClaireAppBundle:Course/Course/Component:viewContent.html.twig',
            array('content' => $introduction)
        );
    }

    /**
     * View introduction
     *
     * @param Request    $request          Request
     * @param int|string $courseIdentifier Course id | slug
     * @param int|string $partIdentifier   Part id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewIntroductionByStatusAction(
        Request $request,
        $courseIdentifier,
        $partIdentifier
    )
    {
        $introduction = $this->get('simple_it.claire.course.part')->getIntroductionByStatus(
            $courseIdentifier,
            $partIdentifier,
            $request->get(CourseResource::STATUS, CourseResource::STATUS_DRAFT)
        );

        return $this->render(
            'SimpleITClaireAppBundle:Course/Course/Component:viewContent.html.twig',
            array('content' => $introduction)
        );
    }
}
