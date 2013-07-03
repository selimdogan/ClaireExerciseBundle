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
     * Edit a part
     *
     * @param Request          $request                   Request
     * @param integer | string $courseIdentifier          Course id | slug
     * @param integer | string $partIdentifier            Part id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $courseIdentifier, $partIdentifier)
    {
        if (RequestUtils::METHOD_GET == $request->getMethod()) {
            $part = $this->get('simple_it.claire.course.part')->get(
                $courseIdentifier,
                $partIdentifier
            );
        } else {
            $part = new PartResource();
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
            return new Response(json_encode($part), 200, array('Content-type'=>'application/json'));
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
}

