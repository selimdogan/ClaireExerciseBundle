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

use SimpleIT\ApiResourcesBundle\Course\PartResource;
use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\AppBundle\Model\AppResponse;
use SimpleIT\AppBundle\Util\RequestUtils;
use SimpleIT\Utils\ArrayUtils;
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
     * @param int | string $courseIdentifier Course id | slug
     * @param int | string $partIdentifier   Part id | slug
     *
     * @return Response
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
    public function editAction(Request $request, $courseIdentifier, $partIdentifier)
    {
        $part = new PartResource();
        if (RequestUtils::METHOD_GET == $request->getMethod()) {
            $part = $this->get('simple_it.claire.course.part')->get(
                $courseIdentifier,
                $partIdentifier
            );
        }

        $form = $this->createFormBuilder($part)
            ->add('title')
            ->getForm();

        if (RequestUtils::METHOD_POST == $request->getMethod() && $request->isXmlHttpRequest()) {
            $form->bind($request);
            if ($form->isValid()) {
                $part = $this->get('simple_it.claire.course.part')->save(
                    $courseIdentifier,
                    $partIdentifier,
                    $part
                );
            }

            return new AppResponse($part);
        }

        return $this->render(
            'SimpleITClaireAppBundle:Course/Part/Component:edit.html.twig',
            array(
                'courseIdentifier' => $courseIdentifier,
                'partIdentifier'   => $partIdentifier,
                'part'             => $part,
                'form'             => $form->createView()
            )
        );
    }

    /**
     * View Part content
     *
     * @param int | string $courseIdentifier Course id | slug
     * @param int | string $partIdentifier   Part id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewContentAction($courseIdentifier, $partIdentifier)
    {
        $partContent = $this->get('simple_it.claire.course.part')->getContent(
            $courseIdentifier,
            $partIdentifier
        );

        return $this->render(
            'SimpleITClaireAppBundle:Course/Part/Component:viewContent.html.twig',
            array(
                'courseIdentifier' => $courseIdentifier,
                'partIdentifier'   => $partIdentifier,
                'partContent'      => $partContent
            )
        );
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
        if (RequestUtils::METHOD_GET == $request->getMethod()) {
            $partContent = $this->get('simple_it.claire.course.part')->getContent(
                $courseIdentifier,
                $partIdentifier
            );
        } elseif (RequestUtils::METHOD_POST == $request->getMethod() && $request->isXmlHttpRequest()
        ) {
            $partContent = $request->get('partContent');
            $partContent = $this->get('simple_it.claire.course.part')->saveContent(
                $courseIdentifier,
                $partIdentifier,
                $partContent
            );

            return new AppResponse($partContent);
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

    /**
     * View pagination
     *
     * @param int | string $courseIdentifier Course id | slug
     * @param int | string $partIdentifier   Part id | slug
     * @param int          $displayLevel     Display level
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewPaginationAction($courseIdentifier, $partIdentifier, $displayLevel)
    {
        $toc = $this->get('simple_it.claire.course.course')->getToc($courseIdentifier);

        return $this->render(
            'SimpleITClaireAppBundle:Course/Course/Component:viewPagination.html.twig',
            array('toc' => $toc, 'displayLevel' => $displayLevel, 'identifier' => $partIdentifier)
        );
    }
}
