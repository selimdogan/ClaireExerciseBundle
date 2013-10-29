<?php

namespace SimpleIT\ClaireAppBundle\Controller\Exercise\Component;

use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource;
use SimpleIT\ApiResourcesBundle\Exercise\ResourceResource;
use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\AppBundle\Util\RequestUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ResourceController
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ResourceController extends AppController
{
    /**
     * Edit a resource type (GET)
     *
     * @param Request $request  Request
     * @param int     $resourceId Resource id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editTypeViewAction(Request $request, $resourceId)
    {
        $resource = $this->get('simple_it.claire.exercise.resource')->getResourceToEdit(
            $resourceId
        );

        $form = $this->createForm(new CourseDisplayLevelType(), $resource);

        return $this->render(
            'SimpleITClaireAppBundle:Course/Course/Component:editDisplayLevel.html.twig',
            array('course' => $resource, 'form' => $form->createView())
        );
    }
}
