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

use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\AppBundle\Util\RequestUtils;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PartContentController
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class PartContentController extends AppController
{
    /**
     * Edit part content
     *
     * @param Request $request          Request
     * @param integer $courseIdentifier Course id | slug
     * @param integer $partIdentifier   Part id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $courseIdentifier, $partIdentifier)
    {
        $partContent = null;
        if (RequestUtils::METHOD_GET == $request->getMethod()) {
            $partContent = $this->get('simple_it.claire.course.part')->getContent(
                $courseIdentifier,
                $partIdentifier
            );
        } elseif (RequestUtils::METHOD_POST == $request->getMethod()) {
            $partContent = $request->get('partContent');
            $partContent = $this->get('simple_it.claire.course.part')->saveContent(
                $courseIdentifier,
                $partIdentifier,
                $partContent
            );
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
