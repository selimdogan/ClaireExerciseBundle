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
     * @param mixed $courseIdentifier Course id | slug
     * @param mixed $partIdentifier   Part id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction($courseIdentifier, $partIdentifier)
    {
        //$part = $this->get('simple_it.claire.course.part')->get($courseIdentifier, $partIdentifier);
        $part = 'test';

//        $part = new PartResource();
//        $form = $this->createFormBuilder($part);
//        $form->add()
        return $this->render(
            'SimpleITClaireAppBundle:Course/Part/Module:edit.html.twig',
            array(
                'courseIdentifier' => $courseIdentifier,
                'partIdentifier' => $partIdentifier,
                'part' => $part
            )
        );
    }
}
