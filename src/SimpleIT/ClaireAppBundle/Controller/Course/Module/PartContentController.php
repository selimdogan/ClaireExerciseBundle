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

namespace SimpleIT\ClaireAppBundle\Controller\Course\Module;

use SimpleIT\AppBundle\Controller\AppController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class PartContentController
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class PartContentController extends AppController
{
    /**
     * Edit content
     *
     * @param integer $courseIdentifier Course id | slug
     * @param integer $partIdentifier   Part id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editShowAction($courseIdentifier, $partIdentifier)
    {
        $partContent = $this->get('simple_it.claire.course.part')->getContent(
            $courseIdentifier,
            $partIdentifier
        );

        return $this->render(
            'SimpleITClaireAppBundle:Course/PartContent/Module:edit.html.twig',
            array(
                'courseIdentifier' => $courseIdentifier,
                'partIdentifier' => $partIdentifier,
                'partContent' => $partContent
            )
        );
    }

    /**
     * Edit content
     *
     * @param Request $request          Request
     * @param integer $courseIdentifier Course id | slug
     * @param integer $partIdentifier   Part id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $courseIdentifier, $partIdentifier)
    {

        $partContent = $request->get('partContent');
        $partContent = $this->get('simple_it.claire.course.part')->saveContent(
            $courseIdentifier,
            $partIdentifier,
            $partContent
        );
        return new Response($partContent);

//        return $this->render(
//            'SimpleITClaireAppBundle:Course/PartContent/Module:edit.html.twig',
//            array(
//                'courseIdentifier' => $courseIdentifier,
//                'partIdentifier' => $partIdentifier,
//                'partContent' => $partContent
//            )
//        );
    }
}
